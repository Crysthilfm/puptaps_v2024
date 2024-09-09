<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    {{-- Fontawesome --}}
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">
    <title>Verify Email</title>
</head>
<body>

    <section class="verify mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">PUP-Taguig Alumni Portal System</div>
                        <div class="card-body">
                        <h5 class="card-title">Email Verification</h5>
                        <p class="card-text">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another. Please check you spam folder in your email account for the verification.</p>

                        <div class="">
                            <form action="{{ route('verification.send') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary float-start">Resend Verification</button>
                            </form>

                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-secondary float-end">Logout</button>
                            </form>
                        </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JS --}}
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.js') }}"></script>
</body>
</html>
