<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verify New Email</title>
</head>

<body style="margin: 0; padding: 0; background-color: #11101a;">
    <div style="max-width: 600px; margin: 0 auto; text-align: left; padding: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="https://i.ibb.co/Rp6fLcd/bi-chat-quote-fill.png"
                style="width: 30%; max-width: 50px; vertical-align: middle;">
        </div>
        <div style="text-align: left;">
            <h1 style="font-size: 2em; font-weight: 900; margin: 0; color: #ffffff;">Hola, {{ $username }}</h1>
            <p style="margin: 10px 0 0; color: #ffffff;">You have requested to change your email address.</p>
            <p style="margin: 10px 0 0; color: #ffffff;">Please click the button below to verify your new email:</p>
            <a href="{{ $verificationUrl }}"
                style="background-color: #ff0000; color: #ffffff; display: inline-block; padding: 10px 20px; text-decoration: none; margin-top: 10px; border-radius: 5px;">Verify
                New Email</a>
            <p style="margin: 10px 0 0; color: #ffffff;">If clicking doesn't work, you can try copying and pasting it to
                your browser:</p>
            <p style="margin: 10px 0 0; color: #ffd700;">If you have any problems, please contact us:
                support@moviequotes.ge</p>
            <p style="margin: 10px 0 0; color: #ffffff;">MovieQuotes Team</p>
        </div>
    </div>
</body>

</html>
