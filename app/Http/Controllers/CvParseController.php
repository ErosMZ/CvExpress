<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCvWithAI;
use App\Models\CvParse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvParseController extends Controller
{
    /**
     * Inicia el análisis AI de un CV (subiendo nuevo o usando el existente).
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'source' => ['required', 'in:new,existing'],
            'cv'     => ['required_if:source,new', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        if ($request->source === 'existing') {
            if (!$user->cv_path) {
                return response()->json(['error' => 'No tienes ningún CV guardado.'], 422);
            }
            $path         = $user->cv_path;
            $originalName = $user->cv_original_name ?? 'cv.pdf';
        } else {
            $file = $request->file('cv');
            $path = $file->storeAs(
                'cvs/' . $user->id,
                'cv_ai_' . now()->format('YmdHis') . '_' . Str::random(6) . '.pdf',
                'local'
            );
            $originalName = $file->getClientOriginalName();

            // Actualiza también el CV del perfil
            $user->update([
                'cv_path'          => $path,
                'cv_original_name' => $originalName,
                'cv_uploaded_at'   => now(),
            ]);
        }

        // Cancela parseos anteriores pendientes y elimina sus jobs de la cola
        $oldParseIds = CvParse::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'processing'])
            ->pluck('id');

        if ($oldParseIds->isNotEmpty()) {
            foreach ($oldParseIds as $oldId) {
                \DB::table('jobs')->whereRaw("payload LIKE ?", ['%"cvParseId":' . $oldId . '%'])->delete();
            }
            CvParse::whereIn('id', $oldParseIds)
                ->update(['status' => 'failed', 'error_message' => 'Cancelado por nuevo análisis']);
        }

        $cvParse = CvParse::create([
            'user_id'          => $user->id,
            'cv_path'          => $path,
            'cv_original_name' => $originalName,
            'status'           => 'pending',
        ]);

        ProcessCvWithAI::dispatch($cvParse->id);

        return response()->json([
            'parse_id' => $cvParse->id,
            'status'   => 'pending',
            'message'  => 'Análisis iniciado',
        ]);
    }

    /**
     * Polling: devuelve el estado actual del parseo.
     */
    public function status(CvParse $cvParse): JsonResponse
    {
        abort_if($cvParse->user_id !== Auth::id(), 403);

        $response = [
            'status'     => $cvParse->status,
            'parse_id'   => $cvParse->id,
            'tokens'     => $cvParse->tokens_used,
            'elapsed_ms' => $cvParse->processing_ms,
        ];

        if ($cvParse->isCompleted()) {
            $response['cv_data'] = Auth::user()->fresh()->cv_data ?? [];
        }

        if ($cvParse->isFailed()) {
            // Comprueba si el job está pendiente de reintento en la cola
            $stillInQueue = \DB::table('jobs')
                ->whereRaw("payload LIKE ?", ['%"cvParseId":' . $cvParse->id . '%'])
                ->exists();

            if ($stillInQueue) {
                // Hay un reintento programado, reportar como processing
                $response['status'] = 'processing';
            } else {
                $response['error'] = $cvParse->error_message;
            }
        }

        return response()->json($response);
    }
}
