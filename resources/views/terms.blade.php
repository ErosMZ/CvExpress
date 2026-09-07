@extends('layouts.app')

@section('title', 'Términos de Uso — CvXpress')
@section('meta_description', 'Términos y condiciones de uso de CvXpress. Conoce tus derechos y obligaciones al utilizar nuestra plataforma.')

@push('styles')
<style>
.legal-hero {
    padding: 7rem 0 3rem;
    background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%);
    border-bottom: 1px solid #e2e8f0;
}
.legal-hero__tag {
    display: inline-flex; align-items: center; gap: .4rem;
    background: #dbeafe; color: #1d4ed8;
    font-size: .75rem; font-weight: 600; letter-spacing: .05em; text-transform: uppercase;
    padding: .3rem .75rem; border-radius: 20px; margin-bottom: 1rem;
}
.legal-hero__title { font-size: 2.25rem; font-weight: 700; color: #0f172a; margin: 0 0 .75rem; }
.legal-hero__meta  { font-size: .875rem; color: #64748b; }

.legal-body { padding: 3.5rem 0 5rem; }
.legal-layout { display: grid; grid-template-columns: 220px 1fr; gap: 3rem; align-items: start; }
@media (max-width: 768px) { .legal-layout { grid-template-columns: 1fr; } }

.legal-toc { position: sticky; top: 88px; }
.legal-toc__title { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .75rem; }
.legal-toc__list  { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: .15rem; }
.legal-toc__list a {
    display: block; font-size: .8rem; color: #64748b; text-decoration: none;
    padding: .35rem .6rem; border-radius: 6px; border-left: 2px solid transparent;
    transition: color 150ms, background 150ms, border-color 150ms;
}
.legal-toc__list a:hover { color: #1d4ed8; background: #eff6ff; border-left-color: #93c5fd; }

.legal-article h2 {
    font-size: 1.15rem; font-weight: 700; color: #0f172a;
    margin: 2.5rem 0 .75rem; padding-bottom: .5rem;
    border-bottom: 1px solid #e2e8f0;
    scroll-margin-top: 100px;
}
.legal-article h2:first-child { margin-top: 0; }
.legal-article h3 { font-size: .95rem; font-weight: 600; color: #1e293b; margin: 1.5rem 0 .5rem; }
.legal-article p  { font-size: .9rem; color: #475569; line-height: 1.75; margin: 0 0 .85rem; }
.legal-article ul { padding-left: 1.25rem; margin: 0 0 .85rem; }
.legal-article li { font-size: .9rem; color: #475569; line-height: 1.75; margin-bottom: .3rem; }
.legal-article a  { color: #2563eb; text-decoration: underline; }
.legal-article strong { color: #1e293b; }

.legal-info-box {
    background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 10px;
    padding: 1rem 1.25rem; margin-bottom: 1.5rem;
    font-size: .875rem; color: #0369a1; line-height: 1.65;
}
.legal-info-box strong { color: #0c4a6e; }
</style>
@endpush

@section('content')
<div class="legal-hero">
    <div class="container">
        <div class="legal-hero__tag">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Legal
        </div>
        <h1 class="legal-hero__title">Términos y Condiciones de Uso</h1>
        <p class="legal-hero__meta">Última actualización: {{ date('d \d\e F \d\e Y') }} · Responsable: CvXpress</p>
    </div>
</div>

<div class="legal-body">
    <div class="container">
        <div class="legal-layout">

            {{-- Índice --}}
            <aside class="legal-toc">
                <p class="legal-toc__title">Contenido</p>
                <ul class="legal-toc__list">
                    <li><a href="#objeto">1. Objeto y aceptación</a></li>
                    <li><a href="#titular">2. Datos del titular</a></li>
                    <li><a href="#servicio">3. Descripción del servicio</a></li>
                    <li><a href="#registro">4. Registro y cuenta</a></li>
                    <li><a href="#planes">5. Planes y pagos</a></li>
                    <li><a href="#devolucion">6. Política de devolución</a></li>
                    <li><a href="#uso-aceptable">7. Uso aceptable</a></li>
                    <li><a href="#propiedad">8. Propiedad intelectual</a></li>
                    <li><a href="#contenido">9. Contenido del usuario</a></li>
                    <li><a href="#disponibilidad">10. Disponibilidad</a></li>
                    <li><a href="#responsabilidad">11. Limitación de responsabilidad</a></li>
                    <li><a href="#modificaciones">12. Modificaciones</a></li>
                    <li><a href="#rescision">13. Rescisión</a></li>
                    <li><a href="#ley">14. Ley aplicable</a></li>
                    <li><a href="#contacto">15. Contacto</a></li>
                </ul>
            </aside>

            {{-- Artículo --}}
            <article class="legal-article">

                <div class="legal-info-box">
                    <strong>Resumen:</strong> Al usar CvXpress aceptas estos términos. Ofrecemos un servicio de creación de CV web y PDF. Los pagos son anuales (CV Web) o por descarga (PDF). Dispones de 14 días de desistimiento. Tratar tus datos con respeto y transparencia es nuestra prioridad.
                </div>

                <h2 id="objeto">1. Objeto y aceptación de los términos</h2>
                <p>Los presentes Términos y Condiciones de Uso (en adelante, «Términos») regulan el acceso y uso de la plataforma CvXpress, accesible en <a href="{{ config('app.url') }}">{{ config('app.url') }}</a> (en adelante, «la Plataforma» o «el Servicio»).</p>
                <p>Al crear una cuenta, acceder al Servicio o realizar cualquier compra, el usuario declara haber leído, comprendido y aceptado íntegramente estos Términos, así como la <a href="{{ route('privacy') }}">Política de Privacidad</a>. Si no estás de acuerdo con alguno de los términos, debes abstenerte de usar el Servicio.</p>
                <p>Estos Términos constituyen el acuerdo completo entre el usuario y CvXpress para el uso de la Plataforma.</p>

                <h2 id="titular">2. Datos del titular del servicio</h2>
                <ul>
                    <li><strong>Denominación:</strong> {{ config('legal.company') }}</li>
                    <li><strong>Titular:</strong> {{ config('legal.owner') }}</li>
                    <li><strong>NIF:</strong> {{ config('legal.nif') }}</li>
                    <li><strong>Dirección:</strong> {{ config('legal.address') }}</li>
                    <li><strong>Correo de contacto:</strong> <a href="mailto:{{ config('legal.email') }}">{{ config('legal.email') }}</a></li>
                </ul>

                <h2 id="servicio">3. Descripción del servicio</h2>
                <p>CvXpress es una plataforma SaaS que permite a los usuarios:</p>
                <ul>
                    <li>Crear y editar currículums en formato web (CV Web) a partir de plantillas prediseñadas.</li>
                    <li>Publicar su CV en un subdominio personalizado de CvXpress.</li>
                    <li>Generar y descargar su currículum en formato PDF.</li>
                    <li>Mejorar el contenido de su CV mediante herramientas de inteligencia artificial.</li>
                    <li>Gestionar su perfil profesional y datos personales.</li>
                </ul>
                <p>El Servicio se ofrece «tal cual», pudiendo variar sus funcionalidades según el plan contratado. CvXpress se reserva el derecho de modificar, ampliar o retirar funcionalidades con notificación previa a los usuarios afectados.</p>

                <h2 id="registro">4. Registro y cuenta de usuario</h2>
                <h3>4.1 Requisitos</h3>
                <p>Para utilizar el Servicio es necesario registrarse con una dirección de correo electrónico válida. El usuario debe tener al menos 16 años de edad.</p>
                <h3>4.2 Veracidad de los datos</h3>
                <p>El usuario se compromete a proporcionar información veraz, exacta y actualizada en el proceso de registro y durante el uso del Servicio.</p>
                <h3>4.3 Seguridad de la cuenta</h3>
                <p>El usuario es responsable de mantener la confidencialidad de su contraseña y de todas las actividades realizadas desde su cuenta. Debe notificarnos inmediatamente de cualquier uso no autorizado a <a href="mailto:erosmunozzanon@gmail.com">erosmunozzanon@gmail.com</a>.</p>
                <h3>4.4 Cuenta única</h3>
                <p>Cada usuario solo puede tener una cuenta activa. Está prohibido ceder o compartir credenciales de acceso.</p>

                <h2 id="planes">5. Planes, precios y facturación</h2>
                <h3>5.1 Planes de suscripción (CV Web)</h3>
                <p>El acceso a plantillas y funcionalidades avanzadas de CV Web está sujeto a planes de suscripción anual. Los precios vigentes en el momento de la contratación son los que se muestran en la página de precios. Los precios incluyen el IVA aplicable.</p>
                <h3>5.2 Pagos únicos (Descarga PDF)</h3>
                <p>La descarga del currículum en formato PDF es un servicio de pago por uso. Cada descarga requiere el abono del importe correspondiente al plan de descarga seleccionado.</p>
                <h3>5.3 Facturación</h3>
                <p>Tras cada pago se emite automáticamente una factura legal descargable desde tu panel de usuario. Los datos de facturación facilitados deben ser verídicos.</p>
                <h3>5.4 Renovación</h3>
                <p>Las suscripciones anuales no se renuevan automáticamente. Recibirás una notificación por correo electrónico antes de la fecha de vencimiento.</p>
                <h3>5.5 Modificación de precios</h3>
                <p>CvXpress podrá modificar los precios de los planes con un preaviso mínimo de 30 días. Los cambios no afectarán a los periodos de suscripción ya abonados.</p>

                <h2 id="devolucion">6. Política de devoluciones y desistimiento</h2>
                <h3>6.1 Derecho de desistimiento (14 días)</h3>
                <p>Conforme al Real Decreto Legislativo 1/2007 (Ley General para la Defensa de los Consumidores y Usuarios), tienes derecho a desistir del contrato en un plazo de <strong>14 días naturales</strong> desde la contratación, sin necesidad de justificación.</p>
                <p>Para ejercer este derecho, comunícalo a <a href="mailto:erosmunozzanon@gmail.com">erosmunozzanon@gmail.com</a> antes de que expire el plazo, indicando tu nombre, correo y número de factura.</p>
                <h3>6.2 Excepción al desistimiento</h3>
                <p>Perderás el derecho de desistimiento si has comenzado a utilizar el Servicio y lo has solicitado expresamente durante el periodo de 14 días (art. 103.m LGDCU). En tal caso, se te cobrará la parte proporcional del servicio utilizado.</p>
                <h3>6.3 Reembolsos</h3>
                <p>Los reembolsos aprobados se tramitarán en un plazo máximo de 14 días mediante el mismo método de pago utilizado en la compra.</p>

                <h2 id="uso-aceptable">7. Uso aceptable del servicio</h2>
                <p>El usuario se compromete a utilizar el Servicio de forma lícita y conforme a estos Términos. Queda expresamente prohibido:</p>
                <ul>
                    <li>Usar el Servicio para actividades ilegales, fraudulentas o que infrinjan derechos de terceros.</li>
                    <li>Publicar contenido falso, difamatorio, obsceno, discriminatorio o que incite al odio.</li>
                    <li>Intentar acceder sin autorización a sistemas, cuentas o datos de otros usuarios.</li>
                    <li>Realizar ingeniería inversa, descompilar o modificar el código fuente de la Plataforma.</li>
                    <li>Utilizar bots, scrapers u otras herramientas automatizadas para extraer datos de la Plataforma.</li>
                    <li>Sobrecargar intencionadamente la infraestructura del Servicio (ataques DoS/DDoS).</li>
                    <li>Revender o sublicenciar el acceso al Servicio sin autorización expresa.</li>
                    <li>Hacerse pasar por otra persona o entidad.</li>
                </ul>
                <p>El incumplimiento de estas normas podrá dar lugar a la suspensión o cancelación inmediata de la cuenta, sin perjuicio de las acciones legales correspondientes.</p>

                <h2 id="propiedad">8. Propiedad intelectual</h2>
                <h3>8.1 Titularidad de CvXpress</h3>
                <p>Todos los derechos de propiedad intelectual sobre la Plataforma, incluyendo su diseño, código fuente, plantillas, logotipos, marcas, textos e imágenes propias, son propiedad exclusiva de CvXpress o de sus licenciantes. Queda prohibida su reproducción, distribución o uso sin autorización expresa.</p>
                <h3>8.2 Licencia de uso al usuario</h3>
                <p>CvXpress concede al usuario una licencia personal, no exclusiva, no transferible y revocable para usar el Servicio y sus plantillas exclusivamente con el fin de crear su propio currículum, conforme a los Términos del plan contratado.</p>
                <h3>8.3 Contenido generado por el usuario</h3>
                <p>El usuario conserva la propiedad intelectual de los contenidos que introduce en la Plataforma (textos, fotografías personales, etc.). Al usar el Servicio, otorga a CvXpress una licencia limitada para almacenar y mostrar dicho contenido con el único fin de prestar el Servicio.</p>

                <h2 id="contenido">9. Contenido del usuario y responsabilidad</h2>
                <p>El usuario es el único responsable del contenido que publique en su CV web. CvXpress actúa como mero intermediario técnico y no revisa ni valida el contenido de los CVs publicados.</p>
                <p>Si CvXpress recibe una notificación de que un contenido publicado infringe derechos de terceros o incumple la normativa aplicable, podrá retirar dicho contenido de forma cautelar y notificarlo al usuario afectado.</p>

                <h2 id="disponibilidad">10. Disponibilidad del servicio</h2>
                <p>CvXpress procurará que la Plataforma esté disponible de forma continua, pero no garantiza una disponibilidad del 100%. Pueden producirse interrupciones por mantenimiento programado (comunicado con antelación), causas de fuerza mayor, fallos de terceros proveedores o incidencias técnicas no previstas.</p>
                <p>CvXpress no será responsable por los daños derivados de interrupciones del Servicio ajenas a su control razonable.</p>

                <h2 id="responsabilidad">11. Limitación de responsabilidad</h2>
                <p>En la máxima medida permitida por la ley aplicable:</p>
                <ul>
                    <li>CvXpress no garantiza que el Servicio sea adecuado para todos los propósitos del usuario ni que esté libre de errores.</li>
                    <li>CvXpress no será responsable de daños indirectos, incidentales, especiales o consecuentes derivados del uso o la imposibilidad de uso del Servicio.</li>
                    <li>La responsabilidad total de CvXpress frente al usuario no excederá el importe abonado por el usuario durante los 12 meses previos al hecho que origina la reclamación.</li>
                </ul>
                <p>Nada en estos Términos excluye o limita la responsabilidad de CvXpress por muerte o lesiones personales causadas por negligencia, fraude o cualquier otra responsabilidad que no pueda ser excluida legalmente.</p>

                <h2 id="modificaciones">12. Modificaciones de los términos</h2>
                <p>CvXpress podrá modificar estos Términos en cualquier momento. Los cambios entrarán en vigor a los 30 días de su publicación en la Plataforma, salvo que la normativa exija un plazo distinto.</p>
                <p>Te notificaremos los cambios relevantes por correo electrónico. El uso continuado del Servicio tras la entrada en vigor de los nuevos Términos implica su aceptación.</p>

                <h2 id="rescision">13. Rescisión y cancelación de cuenta</h2>
                <h3>13.1 Por el usuario</h3>
                <p>Puedes cancelar tu cuenta en cualquier momento desde los ajustes de tu perfil o solicitándolo a <a href="mailto:erosmunozzanon@gmail.com">erosmunozzanon@gmail.com</a>. La cancelación no da derecho a reembolso del periodo no consumido, salvo que se solicite dentro del plazo de desistimiento (sección 6).</p>
                <h3>13.2 Por CvXpress</h3>
                <p>CvXpress puede suspender o cancelar una cuenta, con o sin previo aviso, si el usuario incumple estos Términos, impaga, o si así lo exige la ley. En caso de cancelación no imputable al usuario, se reembolsará la parte proporcional del servicio no consumido.</p>
                <h3>13.3 Efectos de la cancelación</h3>
                <p>Tras la cancelación, perderás el acceso al Servicio y tus datos serán eliminados conforme a nuestra <a href="{{ route('privacy') }}">Política de Privacidad</a>. Los datos de facturación se conservarán durante el periodo legalmente exigido.</p>

                <h2 id="ley">14. Ley aplicable y resolución de conflictos</h2>
                <p>Estos Términos se rigen por la legislación española. Para la resolución de cualquier controversia derivada de estos Términos, las partes se someten, con renuncia expresa a cualquier otro fuero, a los Juzgados y Tribunales de <strong>Valencia (España)</strong>.</p>
                <p>Si eres consumidor en la Unión Europea, también puedes acceder a la plataforma de resolución de litigios en línea de la Comisión Europea en <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener">ec.europa.eu/consumers/odr</a>.</p>

                <h2 id="contacto">15. Contacto</h2>
                <p>Para cualquier consulta sobre estos Términos, puedes contactarnos en:</p>
                <ul>
                    <li><strong>Email:</strong> <a href="mailto:{{ config('legal.email') }}">{{ config('legal.email') }}</a></li>
                    <li><strong>Dirección postal:</strong> {{ config('legal.address') }}</li>
                </ul>

            </article>
        </div>
    </div>
</div>
@endsection
