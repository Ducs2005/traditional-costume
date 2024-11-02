<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
</head>
<body>
  <h1>Reset Your Password</h1>

  @if(isset($data))
    <p>Hello, {{ $data['name'] }}</p>
    <p>Your reset link: <a href="{{ $data['body'] }}">{{ $data['body'] }}</a></p>
  @else
    <p>No data available.</p>
  @endif

  <footer>
    <p>Thank you for using our service!</p>
  </footer>
</body>
</html>
