<?php

namespace App\Jobs;

use App\Models\CvParse;
use App\Services\Cv\CvDataMapper;
use App\Services\Cv\CvTextExtractor;
use App\Services\Cv\OpenAICvParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessCvWithAI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function backoff(): array
    {
        return [10, 30];  // espera 10s en el 1er reintento, 30s en el 2º
    }

    public function __construct(private readonly int $cvParseId) {}

    public function handle(
        CvTextExtractor $extractor,
        OpenAICvParser  $aiParser,
        CvDataMapper    $mapper,
    ): void {
        $cvParse = CvParse::find($this->cvParseId);

        if (!$cvParse || $cvParse->isCompleted()) {
            return;
        }

        $cvParse->markProcessing();
        $startMs = (int)(microtime(true) * 1000);

        try {
            // 1. Extraer texto del PDF
            $rawText = $extractor->extract($cvParse->cv_path);

            if (strlen(trim($rawText)) < 50) {
                throw new \RuntimeException('No se pudo extraer texto. El PDF puede estar escaneado sin texto seleccionable.');
            }

            // 2. OpenAI: texto → JSON estructurado
            ['parsed' => $parsed, 'tokens' => $tokens] = $aiParser->parse($rawText);

            // 3. Convertir al formato del editor
            $cvData = $mapper->toEditorFormat($parsed);

            // 4. Guardar resultado en cv_parses
            $elapsed = (int)(microtime(true) * 1000) - $startMs;
            $cvParse->markCompleted($parsed, $rawText, $tokens, $elapsed);

            // 5. Aplicar a users.cv_data
            $cvParse->user()->update([
                'cv_data'            => $cvData,
                'latest_cv_parse_id' => $cvParse->id,
            ]);

        } catch (\Throwable $e) {
            Log::error('ProcessCvWithAI failed', [
                'cv_parse_id' => $this->cvParseId,
                'error'       => $e->getMessage(),
                'attempt'     => $this->attempts(),
            ]);

            // Rate limit / server errors transitorios → libera el job para
            // que se reintente más tarde sin contar como fallo definitivo
            if ($this->isRetryableError($e)) {
                $cvParse->update(['status' => 'pending']);
                $delay = $this->attempts() <= 1 ? 30 : 90;
                $this->release($delay);
                return;
            }

            $cvParse->markFailed($e->getMessage());
        }
    }

    public function failed(\Throwable $e): void
    {
        // Solo llega aquí cuando se agotan TODOS los intentos
        CvParse::find($this->cvParseId)?->markFailed($e->getMessage());
    }

    private function isRetryableError(\Throwable $e): bool
    {
        $msg = strtolower($e->getMessage());
        return str_contains($msg, 'rate limit')
            || str_contains($msg, 'too many requests')
            || str_contains($msg, '429')
            || str_contains($msg, '503')
            || str_contains($msg, 'timeout')
            || str_contains($msg, 'connection');
    }
}
