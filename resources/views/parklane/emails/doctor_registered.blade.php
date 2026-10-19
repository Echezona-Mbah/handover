<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Doctor Registration Successful</title>
  </head>
  <body style="margin:0; padding:0; background:#f3f5f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#1f2a37;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f3f5f7; padding:32px 0;">
      <tr>
        <td align="center">
          <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 8px 24px rgba(16,24,40,0.08);">

            <!-- Header -->
            <tr>
              <td style="padding:32px 36px; background:linear-gradient(135deg, #10375c, #1f6feb); color:#ffffff;">
                <div style="display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:12px; background:rgba(255,255,255,0.15); margin-bottom:14px;">
                  <span style="font-size:20px;">🩺</span>
                </div>
                <h1 style="margin:0; font-size:24px; font-weight:700; letter-spacing:0.2px;">Welcome to the Care Team</h1>
                <p style="margin:8px 0 0; font-size:14px; opacity:0.9;">Your doctor account is now active</p>
              </td>
            </tr>

            <!-- Greeting + Intro -->
            <tr>
              <td style="padding:32px 36px 8px;">
                <p style="margin:0 0 12px; font-size:18px; font-weight:600; color:#0f172a;">
                  Hello {{ $doctor->full_name }},
                </p>
                <p style="margin:0 0 20px; font-size:15px; line-height:1.65; color:#374151;">
                  We're pleased to let you know that your doctor account has been registered successfully.
                  You can now access the platform to review your department updates and patient assignments.
                </p>
              </td>
            </tr>

            <!-- Account details card -->
            <tr>
              <td style="padding:0 36px 8px;">
                <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;">
                  <tr>
                    <td style="padding:18px 20px; border:1px solid #e5e7eb; border-radius:12px; background:#f9fafb;">
                      <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                          <td style="padding:4px 0; font-size:13px; color:#6b7280; width:40%;">Registered email</td>
                          <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600; text-align:right;">{{ $doctor->email }}</td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0; font-size:13px; color:#6b7280;">Department</td>
                          <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600; text-align:right;">{{ $doctor->doctorDepartment->name ?? $doctor->doctor_departments }}</td>
                        </tr>
                        <tr>
                          <td style="padding:4px 0; font-size:13px; color:#6b7280;">Unit</td>
                          <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600; text-align:right;">{{ $doctor->doctorUnit->name ?? $doctor->doctor_unit_id }}</td>
                        </tr>
                        @if(!empty($doctor->doctor_sub_unit_id))
                        <tr>
                          <td style="padding:4px 0; font-size:13px; color:#6b7280;">Sub-unit</td>
                          <td style="padding:4px 0; font-size:14px; color:#111827; font-weight:600; text-align:right;">{{ $doctor->subUnit->name ?? $doctor->doctor_sub_unit_id }}</td>
                        </tr>
                        @endif
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- CTA -->
            <tr>
              <td style="padding:28px 36px 8px; text-align:center;">
                <p style="margin:0 0 20px; font-size:15px; line-height:1.6; color:#374151;">
                  Click the button below to set your password and log in. This link expires in <strong>48 hours</strong>.
                </p>
                <a href="{{ $resetUrl }}"
                   style="display:inline-block; background:#1f6feb; color:#ffffff; text-decoration:none; font-size:15px; font-weight:600; padding:13px 32px; border-radius:10px; box-shadow:0 4px 12px rgba(31,111,235,0.3);">
                  Set Your Password
                </a>
                <p style="margin:18px 0 0; font-size:12px; color:#9ca3af; word-break:break-all;">
                  Or copy this link: <a href="{{ $resetUrl }}" style="color:#1f6feb;">{{ $resetUrl }}</a>
                </p>
              </td>
            </tr>

            <!-- Security notice -->
            <tr>
              <td style="padding:24px 36px 8px;">
                <p style="margin:0; font-size:13px; color:#6b7280; line-height:1.6;">
                  If you weren't expecting this registration, please contact your hospital administrator immediately.
                </p>
              </td>
            </tr>

            <!-- Support banner -->
            <tr>
              <td style="padding:20px 36px 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                    <td style="padding:16px 18px; background:#0f172a; border-radius:12px;">
                      <p style="margin:0; font-size:14px; color:#ffffff;">
                        Need help? Reply to this email and our support team will assist you.
                      </p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- Footer -->
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