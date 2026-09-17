<?php

namespace App\Http\Controllers;

use App\Models\UserPurchase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * Sirve las webs publicadas por subdominio: {subdominio}.{dominio}.
 *
 * Los ficheros los genera DashboardController::cvWebPublish() (misma
 * plantilla + datos que el ZIP descargable) y quedan guardados en el disco
 * "sites" (ver config/filesystems.php), bajo la carpeta del subdominio.
 */
class PublicSiteController extends Controller
{
    public function show(Request $request, string $subdomain, string $path = '')
    {
        $purchase = UserPurchase::where('subdomain', $subdomain)
            ->where('hosting_type', 'subdomain')
            ->where('status', 'active')
            ->latest()
            ->first();

        abort_unless($purchase, 404);

        $path = trim($path, '/');
        if ($path === '' || str_ends_with($path, '/')) {
            $path .= 'index.html';
        }

        // Nunca salir de la carpeta del subdominio.
        abort_if(str_contains($path, '..'), 404);

        $disk = Storage::disk('sites');
        $file = $subdomain . '/' . $path;

        abort_unless($disk->exists($file), 404);

        $mime = $disk->mimeType($file) ?: 'application/octet-stream';

        return new Response($disk->get($file), 200, [
            'Content-Type' => $mime,
        ]);
    }
}
