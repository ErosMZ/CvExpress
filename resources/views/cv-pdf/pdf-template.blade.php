<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 12px;
    color: #1f2937;
    background: #ffffff;
}

table.cv-layout {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

/* Sidebar transparente — el canvas verde (dibujado antes) se ve a través */
td.cv-sidebar {
    width: 33%;
    background: transparent;
    color: #fff;
    padding: 30px 20px;
    vertical-align: top;
}

/* Main blanco cubre el lado derecho */
td.cv-main {
    width: 67%;
    background: #fff;
    padding: 34px 30px;
    vertical-align: top;
}

/* Photo */
.cv-photo-wrap { text-align: center; margin-bottom: 20px; }
.cv-photo {
    width: 94px; height: 94px;
    border-radius: 47px;
    object-fit: cover; object-position: center 20%;
    border: 3px solid rgba(255,255,255,.35);
    display: block; margin: 0 auto;
}
.cv-photo-placeholder {
    width: 94px; height: 94px;
    border-radius: 47px;
    background: rgba(255,255,255,.18);
    border: 3px solid rgba(255,255,255,.35);
    display: block; margin: 0 auto;
}

/* Sidebar typography */
.cv-sb-title {
    font-size: 9px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1.4px;
    color: rgba(255,255,255,.6);
    border-bottom: 1px solid rgba(255,255,255,.2);
    padding-bottom: 5px; margin: 18px 0 9px;
}
.cv-sb-title.first { margin-top: 0; }
.cv-sb-item {
    font-size: 11px; color: rgba(255,255,255,.9);
    line-height: 1.65; margin-bottom: 5px;
    word-wrap: break-word; overflow-wrap: anywhere;
}
.cv-sb-skill {
    font-size: 11px; color: rgba(255,255,255,.9);
    padding: 3px 0;
    border-bottom: 1px solid rgba(255,255,255,.12);
    margin-bottom: 2px; word-wrap: break-word;
}
.cv-lang-name  { font-size: 11px; color: rgba(255,255,255,.9); }
.cv-lang-level { font-size: 9.5px; color: rgba(255,255,255,.55); }
.cv-lang-row   { margin-bottom: 5px; }
.cv-ref-name   { font-size: 11px; font-weight: 700; color: #fff; margin-bottom: 1px; }
.cv-ref-detail { font-size: 10px; color: rgba(255,255,255,.65); line-height: 1.5; margin-bottom: 9px; }
.cv-cert-name  { font-size: 11px; font-weight: 700; color: rgba(255,255,255,.95); margin-bottom: 1px; }
.cv-cert-meta  { font-size: 9.5px; color: rgba(255,255,255,.6); margin-bottom: 7px; }

/* Main typography */
.cv-name {
    font-family: 'DejaVu Serif', serif;
    font-size: 28px; font-weight: 700;
    color: {{ $color }};
    line-height: 1.15; margin-bottom: 4px;
}
.cv-profession {
    font-size: 11.5px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1.5px;
    color: #374151; margin-bottom: 22px;
}
.cv-section-title {
    font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1.3px;
    color: {{ $color }};
    border-bottom: 1.5px solid {{ $color }};
    padding-bottom: 4px; margin: 20px 0 10px;
}
.cv-section-title.first { margin-top: 0; }
.cv-profile-text { font-size: 11.5px; color: #374151; line-height: 1.75; }

/* Experience */
.cv-exp-item    { margin-bottom: 14px; page-break-inside: avoid; }
.cv-exp-company {
    font-family: 'DejaVu Serif', serif;
    font-size: 13px; font-weight: 700;
    color: {{ $color }}; margin-bottom: 2px;
}
.cv-exp-meta  { font-size: 10.5px; color: #9ca3af; margin-bottom: 3px; }
.cv-exp-desc  { font-size: 11px; color: #4b5563; line-height: 1.65; }

/* Education */
.cv-edu-item   { margin-bottom: 13px; page-break-inside: avoid; }
.cv-edu-dates  { font-size: 10px; color: #9ca3af; margin-bottom: 2px; }
.cv-edu-degree { font-size: 11.5px; font-weight: 700; color: #1f2937; margin-bottom: 1px; }
.cv-edu-school { font-size: 11px; color: #6b7280; margin-bottom: 2px; }
.cv-edu-desc   { font-size: 11px; color: #4b5563; line-height: 1.6; }

/* Projects */
.cv-proj-item { margin-bottom: 14px; page-break-inside: avoid; }
.cv-proj-name { font-size: 12px; font-weight: 700; color: {{ $color }}; margin-bottom: 2px; }
.cv-proj-desc { font-size: 11px; color: #4b5563; line-height: 1.6; margin-bottom: 3px; }
.cv-proj-link { font-size: 10px; color: {{ $color }}; }
</style>
</head>
<body>

{{-- Script ejecutado antes del contenido HTML: dibuja el rectángulo verde
     en el canvas de cada página (sistema coord. PDF: origen abajo-izquierda) --}}
<script type="text/php">
    if (isset($pdf)) {
        $hex = ltrim('{{ $color }}', '#');
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;
        $pageW = $pdf->get_width();
        $pageH = $pdf->get_height();
        // Dibuja la banda verde en el 33% izquierdo, toda la altura
        $pdf->filled_rectangle(0, 0, $pageW * 0.33, $pageH, [$r, $g, $b]);
        // Registra el mismo dibujo para páginas 2+
        $pdf->page_script(function($pageNum, $pageCount, $canvas, $fontMetrics) use ($r, $g, $b) {
            $w = $canvas->get_width();
            $h = $canvas->get_height();
            $canvas->filled_rectangle(0, 0, $w * 0.33, $h, [$r, $g, $b]);
        });
    }
</script>

<table class="cv-layout">
<tr>

{{-- ─────────── SIDEBAR ─────────── --}}
<td class="cv-sidebar">

    <div class="cv-photo-wrap">
        @if($photo)
            <img class="cv-photo" src="{{ $photo }}">
        @else
            <div class="cv-photo-placeholder"></div>
        @endif
    </div>

    <div class="cv-sb-title first">Contacto</div>
    @if($telefono)<div class="cv-sb-item">{{ $telefono }}</div>@endif
    @if($email)<div class="cv-sb-item">{{ $email }}</div>@endif
    @if($ubicacion)<div class="cv-sb-item">{{ $ubicacion }}</div>@endif
    @if($linkedin)<div class="cv-sb-item">{{ $linkedin }}</div>@endif
    @if($portfolio)<div class="cv-sb-item">{{ $portfolio }}</div>@endif

    @php
        $langItems = array_values(array_filter($lang ?? [], fn($l) => !empty($l['idioma'])));
        $habItems  = array_values(array_filter(array_map('trim', explode("\n", $habilidades ?? ''))));
        $refItems  = array_values(array_filter($ref  ?? [], fn($r) => !empty($r['nombre'])));
        $certItems = array_values(array_filter($cert ?? [], fn($c) => !empty($c['nombre'])));
    @endphp

    @if(count($langItems))
    <div class="cv-sb-title">Idiomas</div>
    @foreach($langItems as $l)
    <div class="cv-lang-row">
        <span class="cv-lang-name">{{ $l['idioma'] }}</span>
        @if(!empty($l['nivel'])) <span class="cv-lang-level"> — {{ $l['nivel'] }}</span>@endif
    </div>
    @endforeach
    @endif

    @if(count($habItems))
    <div class="cv-sb-title">Habilidades</div>
    @foreach($habItems as $h)
    <div class="cv-sb-skill">{{ $h }}</div>
    @endforeach
    @endif

    @if(count($refItems))
    <div class="cv-sb-title">Referencias</div>
    @foreach($refItems as $r)
    <div class="cv-ref-name">{{ $r['nombre'] }}</div>
    <div class="cv-ref-detail">
        @php $meta = array_filter([$r['cargo'] ?? '', $r['empresa'] ?? '']); @endphp
        @if($meta){{ implode(' · ', $meta) }}<br>@endif
        @if(!empty($r['email'])){{ $r['email'] }}@endif
    </div>
    @endforeach
    @endif

    @if(count($certItems))
    <div class="cv-sb-title">Certificaciones</div>
    @foreach($certItems as $c)
    <div class="cv-cert-name">{{ $c['nombre'] }}</div>
    @php $cmeta = array_filter([$c['entidad'] ?? '', $c['año'] ?? '']); @endphp
    @if($cmeta)<div class="cv-cert-meta">{{ implode(' · ', $cmeta) }}</div>@endif
    @endforeach
    @endif

</td>

{{-- ─────────── MAIN ─────────── --}}
<td class="cv-main">

    <div class="cv-name">{{ trim(($nombre ?? '') . ' ' . ($apellidos ?? '')) ?: 'Tu Nombre' }}</div>
    @if($profesion)
    <div class="cv-profession">{{ $profesion }}</div>
    @endif

    @if($perfil)
    <div class="cv-section-title first">Perfil</div>
    <div class="cv-profile-text">{!! nl2br(e($perfil)) !!}</div>
    @endif

    @php
        $expItems  = array_values(array_filter($exp  ?? [], fn($e) => !empty($e['empresa']) || !empty($e['cargo'])));
        $eduItems  = array_values(array_filter($edu  ?? [], fn($e) => !empty($e['titulo'])  || !empty($e['centro'])));
        $projItems = array_values(array_filter($proj ?? [], fn($p) => !empty($p['nombre'])));
    @endphp

    @if(count($expItems))
    <div class="cv-section-title">Experiencia Laboral</div>
    @foreach($expItems as $e)
    <div class="cv-exp-item">
        @if(!empty($e['empresa']))<div class="cv-exp-company">{{ $e['empresa'] }}</div>@endif
        @php
            $metaParts = array_filter([
                !empty($e['cargo']) ? $e['cargo'] : null,
                implode(' – ', array_filter([$e['desde'] ?? '', $e['hasta'] ?? ''])) ?: null
            ]);
        @endphp
        @if($metaParts)<div class="cv-exp-meta">{{ implode(' | ', $metaParts) }}</div>@endif
        @if(!empty($e['desc']))<div class="cv-exp-desc">{!! nl2br(e($e['desc'])) !!}</div>@endif
    </div>
    @endforeach
    @endif

    @if(count($eduItems))
    <div class="cv-section-title">Educación</div>
    @foreach($eduItems as $e)
    <div class="cv-edu-item">
        @php $dates = implode(' – ', array_filter([$e['desde'] ?? '', $e['hasta'] ?? ''])); @endphp
        @if($dates)<div class="cv-edu-dates">{{ $dates }}</div>@endif
        @if(!empty($e['titulo']))<div class="cv-edu-degree">{{ $e['titulo'] }}</div>@endif
        @if(!empty($e['centro']))<div class="cv-edu-school">{{ $e['centro'] }}</div>@endif
        @if(!empty($e['desc']))<div class="cv-edu-desc">{!! nl2br(e($e['desc'])) !!}</div>@endif
    </div>
    @endforeach
    @endif

    @if(count($projItems))
    <div class="cv-section-title">Proyectos</div>
    @foreach($projItems as $p)
    <div class="cv-proj-item">
        <div class="cv-proj-name">{{ $p['nombre'] }}</div>
        @if(!empty($p['desc']))<div class="cv-proj-desc">{!! nl2br(e($p['desc'])) !!}</div>@endif
        @if(!empty($p['enlace']))<div class="cv-proj-link">{{ $p['enlace'] }}</div>@endif
    </div>
    @endforeach
    @endif

</td>

</tr>
</table>

</body>
</html>
