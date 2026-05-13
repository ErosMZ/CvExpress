<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvParse extends Model
{
    protected $fillable = [
        'user_id',
        'cv_path',
        'cv_original_name',
        'status',
        'raw_text',
        'parsed_data',
        'error_message',
        'tokens_used',
        'processing_ms',
    ];

    protected $casts = [
        'parsed_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool    { return $this->status === 'pending'; }
    public function isProcessing(): bool { return $this->status === 'processing'; }
    public function isCompleted(): bool  { return $this->status === 'completed'; }
    public function isFailed(): bool     { return $this->status === 'failed'; }

    public function markProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    public function markCompleted(array $parsedData, string $rawText, int $tokens, int $ms): void
    {
        $this->update([
            'status'       => 'completed',
            'parsed_data'  => $parsedData,
            'raw_text'     => $rawText,
            'tokens_used'  => $tokens,
            'processing_ms'=> $ms,
        ]);
    }

    public function markFailed(string $error): void
    {
        $this->update(['status' => 'failed', 'error_message' => $error]);
    }
}
