<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password Request</h1>
    <p>You have requested to reset your password. Click the link below to reset it:</p>
    <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}">Reset Password</a>
    <p>If you did not request a password reset, please ignore this email.</p>
</body>
</html>
