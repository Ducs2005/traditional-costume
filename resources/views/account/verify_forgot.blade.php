<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title></title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      color: #333;
      background-color: #fff;
    }

    .container {
      margin: 0 auto;
      width: 100%;
      max-width: 600px;
      padding: 0 0px;
      padding-bottom: 10px;
      border-radius: 5px;
      line-height: 1.8;
    }

    .header {
      border-bottom: 1px solid #eee;
    }

    .header a {
      font-size: 1.4em;
      color: #000;
      text-decoration: none;
      font-weight: 600;
    }

    .content {
      min-width: 700px;
      overflow: auto;
      line-height: 2;
    }

    .otp {
      background: linear-gradient(to right, #00bc69 0, #00bc88 50%, #00bca8 100%);
      margin: 0 auto;
      width: max-content;
      padding: 0 10px;
      color: black;
      border-radius: 4px;
      text-shadow: 2px 0 #fff, -2px 0 #fff, 0 2px #fff, 0 -2px #fff,
             1px 1px #fff, -1px -1px #fff, 1px -1px #fff, -1px 1px #fff;
    }

    .footer {
      color: #aaa;
      font-size: 0.8em;
      line-height: 1;
      font-weight: 300;
    }

    .email-info {
      color: #666666;
      font-weight: 400;
      font-size: 13px;
      line-height: 18px;
      padding-bottom: 6px;
    }

    .email-info a {
      text-decoration: none;
      color: #00bc69;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <a>Prove Your [company name] Identity</a>
    </div>
    <br />
    <strong>Dear {{ $user->last_name }},</strong>
    <p>
      We have received your [company name] account registration request.
      <br />
      <b>Your OTP code is:</b>
    </p>
    <h2 class="otp">{{ $user->confirm_otp }}</h2>
    <h5>Click on the link below to enter OTP:</h5>
    <a href="{{ route('forgot_otp_view', $token) }}">{{ route('forgot_otp_view', $token) }}</a>
    <p style="font-size: 0.9em">
      <br />
      <br />
      Please ensure the confidentiality of your OTP and do not share
      it with anyone.<br />
      <strong>Do not forward or give this code to anyone.</strong>
      <br />
      <br />
      <strong>Thank you for using [company name].</strong>
      <br />
      <br />
      Best regards,
      <br />
      <strong>[company name]</strong>
    </p>

    <hr style="border: none; border-top: 0.5px solid #131111" />
    <div class="footer">
      <p>This email can't receive replies.</p>
      <p>
        For more information about [app name] and your account, visit
        <strong><a href="{{ route('login') }}" alt="companyABC">[app name]</a></strong> // Công ty ABC
      </p>
    </div>
  </div>
  <div style="text-align: center">
    <div class="email-info">
      © 2024 [company]. All rights
      reserved.
    </div>
  </div>
</body>
</html>

