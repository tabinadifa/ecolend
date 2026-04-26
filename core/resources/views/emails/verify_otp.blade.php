<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Verifikasi OTP Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #ff8c00;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .content h2 {
            margin-top: 0;
            color: #333333;
        }
        .otp-code {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #ff8c00;
            letter-spacing: 5px;
            margin: 30px 0;
            padding: 15px;
            background-color: #fcf4ea;
            border-radius: 8px;
        }
        .footer {
            background-color: #f4f4f4;
            color: #777777;
            text-align: center;
            padding: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>EcoLend</h1>
        </div>
        <div class="content">
            <h2>Halo, {{ $userName }}!</h2>
            <p>Terima kasih telah mendaftar di EcoLend. Untuk menyelesaikan proses registrasi akun Anda, silakan gunakan kode OTP berikut untuk memverifikasi alamat email Anda.</p>
            
            <div class="otp-code">
                {{ $otpCode }}
            </div>
            
            <p>Kode ini hanya berlaku selama <strong>10 menit</strong>. Jika Anda tidak merasa melakukan pendaftaran di aplikasi EcoLend, abaikan email ini.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} EcoLend. All rights reserved.
        </div>
    </div>
</body>
</html>
