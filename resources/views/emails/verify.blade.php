<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Activa tu cuenta · CvXpress</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f9;padding:40px 16px;">
    <tr>
      <td align="center">

        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">

          <!-- Header -->
          <tr>
            <td style="background:#1e40af;padding:32px 48px;text-align:center;">
              <span style="color:#ffffff;font-size:22px;font-weight:800;letter-spacing:-0.5px;">
                Cv<span style="color:#93c5fd;">Xpress</span>
              </span>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:40px 48px 32px;">
              <p style="margin:0 0 6px;color:#6b7280;font-size:13px;">Hola,</p>
              <p style="margin:0 0 24px;color:#111827;font-size:20px;font-weight:700;">
                {{ $name }}
              </p>
              <p style="margin:0 0 28px;color:#4b5563;font-size:15px;line-height:1.7;">
                Gracias por registrarte en <strong>CvXpress</strong>. Para activar tu cuenta
                pulsa el botón de abajo. El enlace caduca en <strong>60 minutos</strong>.
              </p>

              <!-- CTA -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:32px;">
                <tr>
                  <td align="center">
                    <a href="{{ $url }}"
                       style="display:inline-block;background:#1e40af;color:#ffffff;text-decoration:none;font-size:15px;font-weight:600;padding:14px 36px;border-radius:8px;">
                      Activar cuenta
                    </a>
                  </td>
                </tr>
              </table>

              <hr style="border:none;border-top:1px solid #e5e7eb;margin:0 0 20px;">

              <p style="margin:0 0 6px;color:#9ca3af;font-size:12px;">
                Si el botón no funciona, copia este enlace en tu navegador:
              </p>
              <p style="margin:0;word-break:break-all;">
                <a href="{{ $url }}" style="color:#1e40af;font-size:12px;text-decoration:none;">{{ $url }}</a>
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:20px 48px;text-align:center;">
              <p style="margin:0;color:#9ca3af;font-size:11px;line-height:1.6;">
                Si no creaste esta cuenta, ignora este mensaje.<br>
                &copy; {{ date('Y') }} CvXpress
              </p>
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
