<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 40px 0; margin: 0;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <tr>
            <td style="background-color: #0d9488; padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">DelimaCare</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 40px 30px;">
                <h2 style="color: #333333; font-size: 20px; margin-top: 0;">Kode Pemulihan Akun</h2>
                <p style="color: #555555; font-size: 16px; line-height: 1.6;">Halo,</p>
                <p style="color: #555555; font-size: 16px; line-height: 1.6;">Gunakan kode OTP 6-digit di bawah ini untuk mengatur ulang kata sandi Anda di aplikasi DelimaCare:</p>

                {{-- 🔥 INI KODE OTP-NYA 🔥 --}}
                <div style="text-align: center; margin: 30px 0;">
                    <div style="background-color: #f1f5f9; color: #0f172a; padding: 15px 30px; border-radius: 8px; font-size: 32px; font-weight: bold; letter-spacing: 5px; display: inline-block; border: 2px dashed #cbd5e1;">
                        {{ $code }}
                    </div>
                </div>

                <p style="color: #777777; font-size: 14px; line-height: 1.5; margin-bottom: 0;">Jangan berikan kode ini kepada siapa pun. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>
            </td>
        </tr>
    </table>
</body>
</html>
