<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            text-align: center;
        }
        input[type="text"], button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
        .message {
            color: green;
            /* font-size: ; */
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Enter OTP</h2>

    @if (session('message'))
        <p class="message">{{ session('message') }}</p>
    @endif


    <form action="{{ route('otp.verify') }}" method="POST">
        @csrf
        <label for="otp">One-Time Password (OTP)</label>
        <input type="text" name="otp" id="otp" placeholder="Enter the 6-digit code" required maxlength="6" pattern="[0-9]{6}">
        
        <button type="submit">Verify</button>
    </form>

    <form action="{{ route('otp.resend') }}" method="POST" style="margin-top: 10px;">
        @csrf
        <button type="submit">Resend OTP</button>
    </form>
</div>

</body>
</html>
