<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Message</title>
  </head>
  <body style="margin:0; padding:0; background:#f3f5f7; font-family:'Georgia','Times New Roman',serif; color:#1f2a37;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f3f5f7; padding:32px 0;">
      <tr>
        <td align="center">
          <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 8px 24px rgba(16,24,40,0.08);">
            <tr>
              <td style="padding:28px 36px; background:linear-gradient(135deg, #0f172a, #2563eb); color:#ffffff;">
                <h1 style="margin:0; font-size:24px; font-weight:700;">Admin Message</h1>
                <p style="margin:8px 0 0; font-size:14px; opacity:0.9;">A message from the administrator</p>
              </td>
            </tr>
            <tr>
              <td style="padding:32px 36px 12px;">
                <p style="margin:0 0 12px; font-size:18px; font-weight:600;">Hello Dr. {{ $doctor->full_name }},</p>
                <p style="margin:0 0 12px; font-size:16px; line-height:1.6;">
                  {{ $messageText }}
                </p>
              </td>
            </tr>
            <tr>
              <td style="padding:18px 36px; background:#f9fafb; text-align:center;">
                <p style="margin:0; font-size:12px; color:#94a3b8;">
                  This is an automated message. Please do not share your password with anyone.
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
