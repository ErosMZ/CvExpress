@extends('layouts.app')

@section('title', 'Política de Privacidad — CvXpress')
@section('meta_description', 'Política de privacidad de CvXpress. Información sobre el tratamiento de tus datos personales conforme al RGPD.')

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
.legal-layout { display: grid; grid-template-columns: 230px 1fr; gap: 3rem; align-items: start; }
@media (max-width: 768px) { .legal-layout { grid-template-columns: 1fr; } .legal-toc { display: none; } }

.legal-toc { position: sticky; top: 88px; }
.legal-toc__title { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .75rem; }
.legal-toc__list  { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: .15rem; }
.legal-toc__list a {
    display: block; font-size: .79rem; color: #64748b; text-decoration: none;
    padding: .35rem .6rem; border-radius: 6px; border-left: 2px solid transparent;
    transition: color 150ms, background 150ms, border-color 150ms;
}
.legal-toc__list a:hover { color: #1d4ed8; background: #eff6ff; border-left-color: #93c5fd; }
.legal-toc__list .toc-sub { padding-left: 1.1rem; font-size: .75rem; }

.legal-article h2 {
    font-size: 1.1rem; font-weight: 700; color: #0f172a;
    margin: 2.5rem 0 .75rem; padding-bottom: .5rem;
    border-bottom: 1px solid #e2e8f0;
    scroll-margin-top: 100px;
}
.legal-article h2:first-child { margin-top: 0; }
.legal-article h3 { font-size: .95rem; font-weight: 600; color: #1e293b; margin: 1.5rem 0 .45rem; scroll-margin-top: 100px; }
.legal-article h4 { font-size: .875rem; font-weight: 600; color: #334155; margin: 1.25rem 0 .4rem; }
.legal-article p  { font-size: .875rem; color: #475569; line-height: 1.8; margin: 0 0 .85rem; }
.legal-article ul, .legal-article ol { padding-left: 1.35rem; margin: 0 0 .85rem; }
.legal-article li { font-size: .875rem; color: #475569; line-height: 1.8; margin-bottom: .3rem; }
.legal-article a  { color: #2563eb; text-decoration: underline; }
.legal-article strong { color: #1e293b; font-weight: 600; }

.legal-update {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
    padding: .85rem 1.15rem; margin-top: 3rem;
    font-size: .8rem; color: #64748b; text-align: center;
}
.legal-responsable {
    background: #f0f9ff; border: 1.5px solid #bae6fd; border-radius: 12px;
    padding: 1.25rem 1.5rem; margin-bottom: 2rem;
    display: grid; grid-template-columns: 1fr 1fr; gap: .5rem 2rem;
}
@media (max-width: 600px) { .legal-responsable { grid-template-columns: 1fr; } }
.legal-responsable__title {
    grid-column: 1 / -1; font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em; color: #0369a1; margin-bottom: .35rem;
    display: flex; align-items: center; gap: .4rem;
}
.legal-responsable__row { font-size: .825rem; color: #334155; }
.legal-responsable__row strong { color: #0f172a; display: block; font-size: .7rem; text-transform: uppercase; letter-spacing: .05em; color: #64748b; font-weight: 600; margin-bottom: .1rem; }
.legal-responsable__dpo {
    grid-column: 1 / -1; margin-top: .5rem; padding-top: .75rem;
    border-top: 1px solid #bae6fd; font-size: .8rem; color: #475569;
}
</style>
@endpush

@section('content')
<div class="legal-hero">
    <div class="container">
        <div class="legal-hero__tag">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Legal
        </div>
        <h1 class="legal-hero__title">Política de Privacidad</h1>
        <p class="legal-hero__meta">Última actualización: {{ date('d \d\e F \d\e Y') }} · {{ config('legal.company') }}</p>
    </div>
</div>

<div class="legal-body">
    <div class="container">
        <div class="legal-layout">

            <aside class="legal-toc">
                <p class="legal-toc__title">Contenido</p>
                <ul class="legal-toc__list">
                    <li><a href="#intro">1. Introducción</a></li>
                    <li><a href="#datos-personales" class="toc-sub">1.1 Datos personales y no personales</a></li>
                    <li><a href="#alcance" class="toc-sub">1.2 Alcance</a></li>
                    <li><a href="#cambios-politica" class="toc-sub">1.3 Cambios a la política</a></li>
                    <li><a href="#informacion">2. Información recopilada</a></li>
                    <li><a href="#info-usuario" class="toc-sub">2.1 Información del usuario</a></li>
                    <li><a href="#almacenamiento" class="toc-sub">2.2 Almacenamiento</a></li>
                    <li><a href="#fundamento" class="toc-sub">2.3 Fundamento jurídico</a></li>
                    <li><a href="#limitacion">3. Limitación de uso</a></li>
                    <li><a href="#fines">4. Fines y divulgaciones</a></li>
                    <li><a href="#seguridad">5. Seguridad</a></li>
                    <li><a href="#cookies">6. Política de cookies</a></li>
                    <li><a href="#terceros">7. Vínculos a terceros</a></li>
                    <li><a href="#transferencias">8. Transferencias internacionales</a></li>
                    <li><a href="#derechos">9. Ejercicio de derechos</a></li>
                </ul>
            </aside>

            <article class="legal-article">

                <div class="legal-responsable">
                    <div class="legal-responsable__title">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Responsable del Tratamiento · Art. 13 RGPD
                    </div>
                    <div class="legal-responsable__row">
                        <strong>Denominación</strong>
                        {{ config('legal.company') }}
                    </div>
                    <div class="legal-responsable__row">
                        <strong>NIF / DNI</strong>
                        {{ config('legal.nif') }}
                    </div>
                    <div class="legal-responsable__row">
                        <strong>Titular</strong>
                        {{ config('legal.owner') }}
                    </div>
                    <div class="legal-responsable__row">
                        <strong>Dirección</strong>
                        {{ config('legal.address') }}
                    </div>
                    <div class="legal-responsable__row">
                        <strong>Email de contacto</strong>
                        <a href="mailto:{{ config('legal.email') }}">{{ config('legal.email') }}</a>
                    </div>
                    <div class="legal-responsable__row">
                        <strong>Web</strong>
                        {{ config('app.url') }}
                    </div>
                    <div class="legal-responsable__dpo">
                        <strong>Delegado de Protección de Datos (DPD/DPO):</strong> {{ config('legal.company') }} no está obligada a designar un Delegado de Protección de Datos conforme al Art. 37 RGPD, al no realizar tratamientos a gran escala de categorías especiales de datos ni ejercer actividades que requieran una observación habitual y sistemática de interesados a gran escala. Para cualquier consulta relativa a protección de datos puede dirigirse al correo indicado arriba.
                    </div>
                </div>

                <h2 id="intro">1. Introducción</h2>
                <p>En <strong>{{ config('app.url') }}</strong> cuidamos la información personal que nuestros usuarios y clientes (a partir de ahora, el <em>"Usuario"</em>) nos brindan cuando hacen uso de nuestros servicios.</p>
                <p>Esta Política de Privacidad describe cómo se recopilan, utilizan y divulgan los datos personales y no personales que usted le suministra a <strong>{{ config('legal.company') }}</strong> (a partir de ahora, la <em>"Empresa"</em>) cuando accede o utiliza los servicios de la web <strong>{{ config('app.url') }}</strong> (a partir de ahora, la <em>"Web"</em>).</p>

                <h3 id="datos-personales">1.1 Datos personales y no personales</h3>
                <p>Los datos personales (<em>"Datos personales"</em>) hacen referencia a la información que le identifica como una persona concreta, mientras que los datos no personales (<em>"Datos no personales"</em>) hacen referencia a la información que no le identifica como una persona concreta. Tenga en cuenta que en todo momento la Empresa se acogerá a la definición jurídica en vigor para determinar qué son y qué no son los datos personales. Cuando esta Política de Privacidad haga referencia a <em>"información"</em> o <em>"su información"</em>, podrá comprender tanto datos personales como datos no personales.</p>

                <h3 id="alcance">1.2 Alcance de la Política de Privacidad</h3>
                <p>Esta Política de Privacidad cubre únicamente los datos que recopilemos a través de la Web o a través de correos electrónicos intercambiados con el Usuario, y ninguna otra recopilación o procesamiento de datos, incluyendo, sin limitación, las prácticas de recopilación de datos de cualquier asociado o tercero, así como cualquier información que recopilemos a través de sitios web, productos o servicios que no muestren un vínculo directo a esta Política de Privacidad.</p>
                <p>La Empresa podrá modificar esta Política de Privacidad en cualquier momento.</p>

                <h3 id="cambios-politica">1.3 Cambios a la Política de Privacidad</h3>
                <p>Revise la leyenda <em>"Última actualización"</em> en la parte superior de esta página para ver cuándo fue modificada por última vez. Todo cambio entrará en vigor en esa fecha. Al usar la Web o proporcionarnos información tras dichos cambios, habrá aceptado la Política de Privacidad modificada. Si la Empresa utilizara los datos personales de una manera sustancialmente diferente a la indicada en el momento de su recopilación, notificará a los usuarios por email o mediante un aviso en la Web con 30 días de antelación.</p>

                <h2 id="informacion">2. Información recopilada y tiempo de almacenamiento</h2>
                <p>Usted facilita distintos tipos de información para que la Empresa le ofrezca sus servicios.</p>

                <h3 id="info-usuario">2.1 Información que el Usuario facilita a la Empresa</h3>
                <p>Recopilamos sus datos cuando rellena alguno de los formularios de la Web, solicita información, nos envía un correo electrónico, o, en general, cuando nos proporciona datos de forma activa.</p>
                <p>Los datos recopilados pueden comprender, entre otros: nombre de usuario y contraseña, nombre y apellidos, dirección de email, número de teléfono, dirección postal, cargo profesional, formación académica, experiencia laboral, habilidades, idiomas, fotografía de perfil y cualquier otro dato incluido en el currículum o perfil del Usuario.</p>

                <h4>2.1.1 Servicio de creación de CV Web</h4>
                <p>Uno de los servicios que ofrecemos es la creación de un currículum en formato web a partir de una plantilla prediseñada. El resultado es una página web publicada bajo un subdominio de {{ config('legal.company') }}.</p>
                <p>El CV web del Usuario se mantendrá activo mientras tenga una suscripción vigente. Una vez cancelada la suscripción o vencido el plan, la Empresa enviará un email al Usuario con dos opciones:</p>
                <ol>
                    <li>Renovar el plan para mantener el CV web activo durante un año más.</li>
                    <li>Solicitar la eliminación del CV web y de todos sus datos personales asociados. El proceso es irreversible.</li>
                </ol>

                <h4>2.1.2 Servicio de descarga de CV en formato PDF</h4>
                <p>Ofrecemos la posibilidad de generar y descargar el currículum en formato PDF mediante un pago único por descarga. Los datos introducidos en el editor se almacenan localmente en el navegador del Usuario y no se conservan en nuestros servidores más allá del tiempo necesario para generar el documento.</p>

                <h4>2.1.3 Servicio de mejora del CV mediante Inteligencia Artificial</h4>
                <p>La Empresa ofrece una función opcional de mejora de texto mediante inteligencia artificial. Cuando el Usuario activa esta función, el texto de su perfil profesional se envía a la API de OpenAI, que actúa como encargado del tratamiento. Este texto se procesa de forma puntual y no se almacena de forma permanente por parte de la Empresa ni de OpenAI más allá de lo indicado en sus propias políticas de retención.</p>
                <p>Al usar esta función, el Usuario acepta que el fragmento de texto introducido sea procesado por OpenAI. No se envían datos de identificación personal (nombre, email, teléfono) a dicha API.</p>

                <h4>2.1.4 Información recibida a través de su actividad en la Web</h4>
                <p>Como parte del funcionamiento de la Web, la Empresa podrá recopilar y analizar información del dispositivo del Usuario, entre otras: tiempo de actividad en la Web, páginas visitadas, tipo de navegador, sistema operativo, tipo de dispositivo, dirección IP y nombre del dominio desde donde accede.</p>

                <h3 id="almacenamiento">2.2 Almacenamiento de su información</h3>
                <p>La Empresa conservará los datos personales del Usuario únicamente durante el tiempo necesario para los fines descritos en esta Política de Privacidad.</p>
                <p>Los datos del perfil y del currículum se mantendrán almacenados mientras la cuenta esté activa. Una vez eliminada la cuenta, los datos se borran en un plazo máximo de 30 días, a excepción de los datos de facturación, que se conservarán durante 5 años conforme a la normativa fiscal española.</p>
                <p>Durante el tiempo en que la Empresa posea los datos personales del Usuario, garantiza que no se utilizarán para ningún fin distinto de los descritos en esta Política de Privacidad.</p>
                <p>El Usuario podrá ejercitar en cualquier momento su derecho a la eliminación inmediata de sus datos personales solicitándolo a través de:</p>
                <ul>
                    <li>Nuestro formulario de contacto en <a href="{{ route('contact') }}">{{ config('app.url') }}/contact</a>.</li>
                    <li>A través del email <a href="mailto:{{ config('legal.email') }}">{{ config('legal.email') }}</a>.</li>
                    <li>Mediante los ajustes de su cuenta en el área privada.</li>
                </ul>

                <h4>2.2.1 Almacenamiento del correo electrónico</h4>
                <p>El correo electrónico y nombre del Usuario se mantendrán almacenados durante el periodo en que la cuenta esté activa y hasta 4 años tras su cancelación, con el fin de gestionar posibles reclamaciones y cumplir con las obligaciones legales. La Empresa garantiza que, bajo ningún concepto, se utilizarán los correos electrónicos almacenados en su base de datos para fines publicitarios, de marketing o de promoción de productos o servicios propios o de terceros.</p>

                <h3 id="fundamento">2.3 Fundamento jurídico del tratamiento</h3>

                <h4>2.3.1 Ejecución de un contrato</h4>
                <p>El procesamiento de la mayoría de datos personales se realiza porque es necesario para la ejecución del contrato suscrito con el Usuario o para tomar las medidas necesarias a su petición antes de suscribir dicho acuerdo.</p>

                <h4>2.3.2 Cumplimiento con una obligación legal</h4>
                <p>En algunos casos el tratamiento es necesario para que la Empresa cumpla con sus obligaciones legales, como la conservación de registros de facturación durante los periodos fijados por la normativa fiscal española.</p>

                <h4>2.3.3 Consentimiento</h4>
                <p>En determinadas situaciones, la Empresa depende del consentimiento del Usuario para procesar sus datos personales. Cuando sea necesario, solicitaremos su consentimiento de forma expresa en el momento de la prestación, y el procesamiento solo se realizará una vez garantizado dicho consentimiento.</p>

                <h2 id="limitacion">3. Limitación de uso o divulgación de datos personales</h2>

                <h3>3.1 Cuando el Usuario solicita la eliminación de sus datos personales</h3>
                <p>El Usuario podrá ejercitar en cualquier momento su derecho a la eliminación inmediata de cualquier dato personal que mantengamos en nuestra base de datos, solicitándolo a través de los medios indicados en el apartado 2.2.</p>
                <p>En un plazo máximo de 72 horas todos sus datos personales serán eliminados de forma irreversible. Si el Usuario usa la misma dirección de email para crear una nueva cuenta, los datos existentes en la cuenta anterior no estarán disponibles.</p>

                <h3>3.2 Datos personales necesarios</h3>
                <p>En el momento de la prestación del servicio, la Empresa indicará si necesita datos personales específicos para ofrecerle determinados servicios. Si no se facilitan los datos necesarios, es posible que el servicio en cuestión no esté disponible.</p>

                <h3>3.3 Edad mínima de nuestros Usuarios</h3>
                <p>La Empresa limita el procesamiento de datos personales a Usuarios mayores de 16 años y adopta medidas para intentar asegurar que no se acepten Usuarios que no cumplan este requisito.</p>

                <h2 id="fines">4. Fines, usos y divulgaciones de información</h2>
                <p>La Empresa gestiona los datos personales de los Usuarios con las siguientes finalidades:</p>
                <ul>
                    <li>Realizar los servicios contratados, así como su mantenimiento y seguimiento.</li>
                    <li>Mantener contacto con el Usuario, gestionar los servicios contratados y dar respuesta a incidencias o consultas.</li>
                    <li>Gestionar y dar acceso al área privada del Usuario.</li>
                    <li>Emitir facturas y cumplir con las obligaciones fiscales y contables.</li>
                </ul>

                <h3>4.1 Envío de material promocional y publicitario</h3>
                <p>La Empresa garantiza que los correos electrónicos almacenados en sus bases de datos, u otra información de contacto proporcionada por el Usuario, no se utilizarán bajo ningún concepto para fines publicitarios, de marketing o de promoción de sus productos o servicios, ni de terceros, salvo que el Usuario haya otorgado su consentimiento expreso para ello.</p>

                <h3>4.2 Información de contacto</h3>
                <p>El Usuario, al proporcionar su información de contacto a la Empresa, acepta que ésta pueda utilizarla para los fines descritos en esta Política de Privacidad. En concreto, acepta que la Empresa pueda comunicarse con él a través del correo electrónico o de los canales de soporte habilitados en la Web.</p>

                <h3>4.3 Prevención del fraude</h3>
                <p>La Empresa puede utilizar la información obtenida para ayudar a diagnosticar problemas con la Web, evitar actividades potencialmente fraudulentas o ilegales y proteger a los Usuarios. La Empresa puede investigar y divulgar información si cree de buena fe que ello es necesario para: cumplir con procesos legales; prevenir, investigar o identificar posibles irregularidades; o proteger los derechos, reputación, propiedad o seguridad de la Empresa o del público.</p>

                <h3>4.4 Facilidades de pago</h3>
                <p>La Empresa no tiene acceso directo a los datos de las tarjetas de crédito ni de las cuentas bancarias del Usuario. Los pagos se procesan actualmente de forma simulada en entorno de demostración. Cuando se integren pasarelas de pago reales (como Stripe o PayPal), los datos de pago se enviarán directamente a dichos proveedores, que disponen de los más altos estándares de seguridad PCI. La Empresa en ningún momento podrá ver, almacenar ni acceder a los datos de pago del Usuario.</p>

                <h2 id="seguridad">5. Seguridad</h2>
                <p>La Empresa procura utilizar medidas de seguridad razonables para ayudar en la protección contra pérdidas, uso indebido y alteración de los datos personales bajo su control:</p>
                <ul>
                    <li>Transmisión cifrada mediante TLS/HTTPS en todas las comunicaciones.</li>
                    <li>Contraseñas almacenadas con hash seguro (bcrypt).</li>
                    <li>Protección contra ataques CSRF, XSS e inyección SQL.</li>
                    <li>Acceso a los datos restringido a personal autorizado bajo deber de confidencialidad.</li>
                    <li>Copias de seguridad periódicas.</li>
                </ul>
                <p>No obstante, ningún método de transmisión por Internet o de almacenamiento electrónico es 100% seguro. Por lo tanto, si bien nos esforzamos por proteger su información, no podemos garantizar su seguridad al 100%. En caso de brecha de seguridad que afecte a sus derechos, le notificaremos sin dilación indebida y notificaremos a la AEPD dentro de las 72 horas siguientes conforme al artículo 33 del RGPD.</p>

                <h2 id="cookies">6. Política de cookies</h2>
                <p>Las cookies son pequeños fragmentos de texto que los sitios web envían al navegador del Usuario con fines de registro y personalización. Una cookie normalmente contendrá el nombre del dominio del que proviene, su duración y un valor único de identificación.</p>
                <p>Las cookies que empleamos en {{ config('legal.company') }} son <strong>cookies propias técnicas</strong>, estrictamente necesarias para el funcionamiento del servicio:</p>
                <ul>
                    <li><strong>Cookies de sesión (XSRF-TOKEN, laravel_session):</strong> Autentican la sesión del Usuario y protegen contra ataques CSRF. Se eliminan al cerrar el navegador.</li>
                    <li><strong>Cookies de identificación:</strong> Almacenan de forma cifrada la sesión del Usuario autenticado para mantener el acceso al área privada.</li>
                    <li><strong>Cookies de preferencias:</strong> Almacenan ajustes de la interfaz. Duración máxima: 13 meses.</li>
                </ul>
                <p>No utilizamos cookies de seguimiento, publicidad ni analíticas de terceros. No es necesario su consentimiento para las cookies técnicas, ya que son imprescindibles para el servicio.</p>
                <p>El Usuario tiene la posibilidad de configurar su navegador para ser avisado de la recepción de cookies y para impedir su instalación. Consulte las instrucciones de su navegador:</p>
                <ul>
                    <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
                    <li><a href="http://support.mozilla.org/kb/delete-cookies-remove-info-websites-stored" target="_blank" rel="noopener">Mozilla Firefox</a></li>
                    <li><a href="https://support.apple.com/kb/ph11920" target="_blank" rel="noopener">Safari</a></li>
                    <li><a href="https://support.microsoft.com/es-es/windows/eliminar-y-administrar-cookies-168dab11-0753-043d-7c16-ede5947fc64d" target="_blank" rel="noopener">Microsoft Edge</a></li>
                </ul>

                <h2 id="terceros">7. Vínculos a sitios de terceros</h2>
                <p>La Web puede poner a disposición del Usuario vínculos a otros sitios web. Al hacer clic en estos vínculos, puede salir de la Web. No nos responsabilizamos de la recopilación de información, uso, divulgación u otras prácticas de privacidad de terceros. Le recomendamos revisar las políticas de privacidad de cualquier sitio web que visite.</p>

                <h2 id="transferencias">8. Transferencias internacionales de datos</h2>
                <p>Con carácter general, la Empresa trata los datos personales dentro del Espacio Económico Europeo (EEE). No obstante, determinados servicios auxiliares implican transferencias a países terceros:</p>

                <h3>8.1 OpenAI (función de mejora por IA)</h3>
                <p>Cuando el Usuario utiliza la función opcional de mejora del CV mediante Inteligencia Artificial, el fragmento de texto introducido se transfiere a <strong>OpenAI, L.L.C.</strong>, con sede en los Estados Unidos. Esta transferencia se realiza al amparo de las <strong>Cláusulas Contractuales Tipo</strong> aprobadas por la Comisión Europea (Decisión de Ejecución 2021/914/UE), que ofrecen garantías adecuadas conforme al Art. 46 RGPD.</p>
                <p>OpenAI actúa en calidad de encargado del tratamiento. El texto procesado no incluye datos de identificación personal (nombre, email, teléfono). Para más información sobre las salvaguardas de OpenAI, consulte su <a href="https://openai.com/policies/privacy-policy" target="_blank" rel="noopener">política de privacidad</a>.</p>

                <h3>8.2 Futuros proveedores de pago</h3>
                <p>Cuando se integren pasarelas de pago reales (p. ej. Stripe Inc., con sede en EE.UU.), los datos de facturación se transferirán a dichos proveedores bajo Cláusulas Contractuales Tipo o mecanismos equivalentes reconocidos por la normativa europea. Esta política se actualizará con los detalles específicos en el momento de dicha integración.</p>

                <h2 id="derechos">9. Ejercicio de sus derechos en relación con sus datos personales</h2>
                <p>Conforme al Reglamento General de Protección de Datos (RGPD) y la LOPDGDD, el Usuario puede ejercer los siguientes derechos:</p>

                <h3>8.1 Derecho de rectificación</h3>
                <p>El Usuario puede editar y actualizar sus datos personales desde su área privada. Para datos que no sean editables directamente, puede presentar una solicitud formal enviando un email a <a href="mailto:{{ config('legal.email') }}">{{ config('legal.email') }}</a>.</p>

                <h3>8.2 Derecho de oposición o restricción del tratamiento</h3>
                <p>Si desea oponerse al procesamiento de sus datos personales o restringirlo, puede contactarnos a través del correo electrónico <a href="mailto:{{ config('legal.email') }}">{{ config('legal.email') }}</a>.</p>

                <h3>8.3 Derecho de eliminación («derecho al olvido»)</h3>
                <p>El Usuario podrá ejercitar en cualquier momento su derecho a la eliminación inmediata de cualquier dato personal solicitándolo a través de:</p>
                <ul>
                    <li>Nuestro formulario de contacto en <a href="{{ route('contact') }}">{{ config('app.url') }}/contact</a>.</li>
                    <li>A través del email <a href="mailto:{{ config('legal.email') }}">{{ config('legal.email') }}</a>.</li>
                    <li>Mediante los ajustes de privacidad de su área privada.</li>
                </ul>
                <p>En un plazo máximo de <strong>72 horas</strong> todos sus datos personales serán eliminados de forma irreversible.</p>

                <h3>8.4 Derecho de portabilidad y acceso</h3>
                <p>Puede solicitar una copia de sus datos personales en formato estructurado y legible enviando su solicitud a <a href="mailto:{{ config('legal.email') }}">{{ config('legal.email') }}</a>. Responderemos en un plazo máximo de 30 días.</p>

                <h3>8.5 Reclamación ante la autoridad de control</h3>
                <p>Si considera que el tratamiento de sus datos no es conforme a la normativa vigente, puede presentar una reclamación ante la <strong>Agencia Española de Protección de Datos (AEPD)</strong> en <a href="https://www.aepd.es" target="_blank" rel="noopener">www.aepd.es</a>.</p>

                <div class="legal-update">
                    <strong>Última actualización de esta Política de Privacidad:</strong> {{ date('d \d\e F \d\e Y') }}
                </div>

            </article>
        </div>
    </div>
</div>
@endsection
