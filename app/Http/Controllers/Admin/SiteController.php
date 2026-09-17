<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Panel de administración: webs publicadas por subdominio.
 *
 * Una fila (UserPurchase con hosting_type=subdomain) puede estar en dos
 * estados: "reservada" (el usuario eligió el nombre pero nunca le dio a
 * publicar / los ficheros no existen en el disco "sites") o "publicada"
 * (los ficheros existen y la web responde en {subdominio}.{dominio}).
 */
class SiteController extends Controller
{
    public function index(Request $request)
    {
        $base = UserPurchase::where('hosting_type', 'subdomain')
            ->whereNotNull('subdomain');

        if ($request->filled('search')) {
            $q = $request->search;
            $base->where(function ($sub) use ($q) {
                $sub->where('subdomain', 'like', "%$q%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%$q%")->orWhere('email', 'like', "%$q%");
                    });
            });
        }

        if ($request->filled('filter')) {
            $publishedDirs = collect(Storage::disk('sites')->directories())->map(fn ($d) => trim($d, '/'));
            match ($request->filter) {
                'live'    => $base->whereIn('subdomain', $publishedDirs),
                'pending' => $base->whereNotIn('subdomain', $publishedDirs),
                default   => null,
            };
        }

        $sites = $base->with('user:id,name,email')->latest()->paginate(20)->withQueryString();

        $domain     = config('services.public_sites.domain');
        $disk       = Storage::disk('sites');
        $scheme     = $request->getScheme();
        $port       = $request->getPort();
        $portSuffix = in_array($port, [80, 443], true) ? '' : ':' . $port;

        $sites->getCollection()->transform(function (UserPurchase $purchase) use ($disk, $domain, $scheme, $portSuffix) {
            $purchase->is_live   = $disk->exists($purchase->subdomain . '/index.html');
            $purchase->site_url  = $scheme . '://' . $purchase->subdomain . '.' . $domain . $portSuffix . '/';
            return $purchase;
        });

        // Estadísticas sobre el total (sin el filtro de búsqueda actual).
        $allSubdomains  = UserPurchase::where('hosting_type', 'subdomain')->whereNotNull('subdomain')->pluck('subdomain');
        $publishedDirs  = collect(Storage::disk('sites')->directories())->map(fn ($d) => trim($d, '/'));
        $stats = [
            'total'   => $allSubdomains->count(),
            'live'    => $allSubdomains->intersect($publishedDirs)->count(),
            'pending' => $allSubdomains->diff($publishedDirs)->count(),
        ];

        return view('admin.sites.index', compact('sites', 'domain', 'stats'));
    }

    public function destroy(UserPurchase $purchase)
    {
        abort_unless($purchase->hosting_type === 'subdomain' && $purchase->subdomain, 404);

        $subdomain = $purchase->subdomain;

        Storage::disk('sites')->deleteDirectory($subdomain);

        $purchase->update([
            'hosting_type' => 'none',
            'subdomain'    => null,
        ]);

        return back()->with('success', "Web «{$subdomain}» eliminada. El usuario podrá reservar un nuevo subdominio cuando quiera.");
    }
}
