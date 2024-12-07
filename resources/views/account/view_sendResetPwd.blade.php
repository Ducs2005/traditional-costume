<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  @if(isset($data))
  <title>{{ $data['bold_text'] }}</title>
  @endif
</head>
<body style="background-color: #f4f4f4; margin: 0; padding: 25px; font-family: Arial, sans-serif;">

  @if(isset($data))
  <div class="frame" style="width: 100%; max-width: 700px; margin: 0 auto; background-color: #f4f4f4; padding: 20px; box-sizing: border-box;">
    <div class="email-wrapper" style="background-color: #fff; padding: 20px 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); border: 2px solid #720808;">
      <h1 style="font-size: 28px; color: #7f241a; text-align: center; margin-top: 0; padding-bottom: 20px;">{{ $data['bold_text'] }}</h1>
      <p style="font-size: 18px; color: #333; text-align: left; margin-bottom: 15px;">Hello <strong style="font-weight: bold; color: #7f241a;">{{ $data['name'] }}</strong></p>
      <p style="font-size: 16px; color: #333; text-align: left; margin-bottom: 15px;">Your reset link:
        <a href="{{ $data['body'] }}" style="color: #1175b8; text-decoration: none; font-weight: bold;">{{ $data['body'] }}</a>
      </p>
      <p style="font-size: 16px; color: #333; text-align: left; margin-bottom: 30px;">Thank you for using our service!</p>
    </div>
    <div class="footer" style="text-align: center; font-size: 14px; color: #701111; margin-top: 20px; padding: 15px; background-color: #f7f7f7; border-radius: 8px; border: 1px solid #dcdcdc;">
        <p style="margin: 0;">If you did not request this password reset, please ignore this email.</p>
    </div>
  </div>
  @else
    <p style="font-size: 16px; color: #333; text-align: center;">No data available.</p>
  @endif

</body>
</html>
