<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Confirma tu cuenta · CvXpress</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f9;padding:40px 16px;">
    <tr>
      <td align="center">

        <!-- Card -->
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">

          <!-- Header con gradiente -->
          <tr>
            <td style="background:linear-gradient(135deg,#1e40af 0%,#3b82f6 60%,#60a5fa 100%);padding:40px 48px 36px;text-align:center;">
              <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td align="center">
                    <!-- Logo -->
                    <div style="display:inline-block;background:rgba(255,255,255,.15);border-radius:12px;padding:10px 20px;margin-bottom:20px;">
                      <span style="color:#ffffff;font-size:22px;font-weight:800;letter-spacing:-0.5px;">
                        Cv<span style="color:#bfdbfe;">Xpress</span>
                      </span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td align="center">
                    <!-- Icono sobre -->
                    <div style="width:64px;height:64px;background:rgba(255,255,255,.2);border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;line-height:64px;text-align:center;font-size:28px;">
                      ✉️
                    </div>
                  </td>
                </tr>
                <tr>
                  <td align="center">
                    <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;line-height:1.3;">
                      ¡Confirma tu correo!
                    </h1>
                    <p style="margin:8px 0 0;color:#bfdbfe;font-size:15px;line-height:1.5;">
                      Estás a un paso de crear tu CV profesional
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Cuerpo -->
          <tr>
            <td style="padding:40px 48px 32px;">
              <p style="margin:0 0 8px;color:#6b7280;font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;">
                Hola,
              </p>
              <p style="margin:0 0 24px;color:#111827;font-size:22px;font-weight:700;">
                {{ $name }} 👋
              </p>
              <p style="margin:0 0 28px;color:#4b5563;font-size:15px;line-height:1.7;">
                Gracias por registrarte en <strong>CvXpress</strong>. Para activar tu cuenta y empezar a crear tu currículum profesional, haz clic en el botón de abajo.
              </p>

              <!-- Botón CTA -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
                <tr>
                  <td align="center">
                    <a href="{{ $url }}"
                       style="display:inline-block;background:linear-gradient(135deg,#1e40af,#3b82f6);color:#ffffff;text-decoration:none;font-size:16px;font-weight:700;padding:16px 40px;border-radius:10px;letter-spacing:.01em;box-shadow:0 4px 12px rgba(59,130,246,.4);">
                      Verificar mi cuenta
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Separador -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
                <tr>
                  <td style="border-top:1px solid #e5e7eb;"></td>
                </tr>
              </table>

              <!-- Info adicional -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f9fafb;border-radius:10px;padding:20px;margin-bottom:24px;">
                <tr>
                  <td style="padding:16px 20px;">
                    <p style="margin:0 0 12px;color:#374151;font-size:14px;font-weight:600;">
                      ¿Qué puedes hacer con CvXpress?
                    </p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td style="padding:4px 0;color:#4b5563;font-size:14px;">
                          <span style="color:#3b82f6;font-weight:700;margin-right:8px;">✓</span>Crea tu CV profesional en minutos
                        </td>
                      </tr>
                      <tr>
                        <td style="padding:4px 0;color:#4b5563;font-size:14px;">
                          <span style="color:#3b82f6;font-weight:700;margin-right:8px;">✓</span>Analiza tu CV con inteligencia artificial
                        </td>
                      </tr>
                      <tr>
                        <td style="padding:4px 0;color:#4b5563;font-size:14px;">
                          <span style="color:#3b82f6;font-weight:700;margin-right:8px;">✓</span>Elige entre plantillas diseñadas por profesionales
                        </td>
                      </tr>
                      <tr>
                        <td style="padding:4px 0;color:#4b5563;font-size:14px;">
                          <span style="color:#3b82f6;font-weight:700;margin-right:8px;">✓</span>Publica tu CV online con tu propio enlace
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- URL alternativa -->
              <p style="margin:0 0 6px;color:#9ca3af;font-size:12px;">
                Si el botón no funciona, copia y pega este enlace en tu navegador:
              </p>
              <p style="margin:0;word-break:break-all;">
                <a href="{{ $url }}" style="color:#3b82f6;font-size:12px;text-decoration:none;">{{ $url }}</a>
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:24px 48px;text-align:center;">
              <p style="margin:0 0 8px;color:#6b7280;font-size:12px;line-height:1.6;">
                Este enlace de verificación caduca en <strong>60 minutos</strong>.<br>
                Si no creaste esta cuenta, puedes ignorar este correo.
              </p>
              <p style="margin:12px 0 0;color:#9ca3af;font-size:11px;">
                © {{ date('Y') }} CvXpress · Todos los derechos reservados
              </p>
            </td>
          </tr>

        </table>
        <!-- fin card -->

      </td>
    </tr>
  </table>

</body>
</html>
