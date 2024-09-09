<!DOCTYPE html>
<html>
<head>
<style>
  /* Reset some default styling */
  body, div, p {
    margin: 0;
    padding: 0;
  }

  /* Main container */
  .container {
    background-color: #f2f2f2;
    font-family: Arial, sans-serif;
    padding: 20px;
  }

  /* Header */
  .header {
    background-color: #800000; /* Maroon */
    color: #ffffff;
    text-align: left;
    padding: 10px;
  }

  /* Content */
  .content {
    padding: 20px;
    background-color: #ffffff;
    border-radius: 5px;
    margin-top: 10px;
  }

  /* Call to action button */
  .cta-button {
    display: inline-block;
    background-color: #FF9900;
    color: #ffffff;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 15px;
    color:white;
    font-style:normal;
  }

  .imgLogoEmail {
    height:100px;
    width:100px;
  }

  /* Footer */
  .footer {
    margin-top: 20px;
    text-align: center;
    color: #888888;
  }
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="{{ asset('img/pupLogo.png') }}" alt="" class="imgLogoEmail">
      <h1>Stay Connected with PUP-Taguig, Alumni</h1>
    </div>
    <div class="content">
      <p>From: <strong>puptalumniportal@gmail.com</strong></p>
      {{-- <p>To: <strong>@alumniemail</strong></p> --}}
      <p>Subject: Alumni Tracer Form Reminder</p>
      <p>Hello PUP Alumni,</p>
      <p>We hope this email finds you well. We kindly remind you to participate in our tracer form to help us keep your information up-to-date. Your feedback is valuable in enhancing our programs and services for alumni.</p>
      <p>Please take a moment to complete the tracer form:</p>
      <br>
      <p>Please log in using your alumni account and update your alumni data or if you haven't created an account yet, sign up with your student number</p>
      <p>You may contact us through messenger https://www.facebook.com/Crysthilfm or through email michaellangpogi@gmail.com to help you with creating an account</p>
      <br>
      <p>!!! If you haven't created an account yet with our system, we would very much appreciate
        your cooperation to create one !!!
      </p>
      <a class="cta-button" href="https://puptaps.pupt-bsit.net/tracer/update-tracer"><b>Complete Tracer Form</b></a>
    </div>

    <div class="footer">
      <p>If you have any questions, please contact us at michaellangpogi@gmail.com (developer) or puptaguig.alumniportal@gmail.com</p>
      <p>Thank you for your continued support!</p>
    </div>
  </div>
</body>
</html>
