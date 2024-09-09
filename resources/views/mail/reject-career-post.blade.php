<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: "Avant Garde", Avantgarde, "Century Gothic", CenturyGothic, AppleGothic, sans-serif;
            color: #000000;
        }
        .header {
            font-size: 30px;
            font-weight: bold;
        }
        .header-logo {
            height: 80px;
            width: 80px;
        }
        .logo {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
        <center>
            <div class="logo">
                <img src="{{ asset('img/pupLogo.png') }}" alt="" class="header-logo">
                <img src="{{ asset('img/puptaps-logo.png') }}" alt="" class="header-logo">
            </div>
            <p class="header">Polytechnic University of the Philippines Taguig Branch
                <span style="font-size: 25px; font-weight: bold;"><br>Alumni Portal System</span>
            </p>
        </center>

    <br>
    <p>Greetings,</p>
    <br>
    <p>We are sorry to announce that your post has been rejected by the faculty/administrators of PUPT - Alumni Portal System. It is due to: {{ $reason }}. We hope that you understand our decision. Moving on, if you wanted to create a post in our Career page you can do so and we also recommend that you check our guidelines before posting.</p>

    <br>
    <p>Padayon!</p>

    <br>
    <br>
    @component('mail::button', ['url' => route('landingPage')])
        GO TO PUPT - Alumni Portal System
    @endcomponent

    <p style="font-size: 10px; text-align: center;">&#169;PUPT-Alumni Portal System
    </p>
</body>
</html>
