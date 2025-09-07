<!doctype html>
<html lang="en">

<head>
    @include('admin.body.head')
    <title>Verify Email - HR Management</title>
</head>

<body class="">
    <!-- wrapper -->
    <div class="wrapper">
        <div class="authentication-forgot d-flex align-items-center justify-content-center">
            <div class="card forgot-box">
                <div class="card-body">
                    <div class="p-3">
                        <div class="text-center">
                            <img src="assets/images/icons/email-verification.png" width="300" alt="" />
                        </div>
                        <h4 class="mt-5 font-weight-bold">Verify Email Address</h4>
                        <label class="form-label-auto">Thanks for signing up! Before getting started, could you verify
                            your email address by clicking on the link we just emailed to you? If you didn't receive
                            the email, we will gladly send you another.</label>
                        <div class="my-4">
                            @if (session('status') == 'verification-link-sent')
                                <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                                    <div class="text-white">
                                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                    </div>
                                </div>
                            @endif


                            <div class="d-grid gap-2">
                                <div class="mt-4 flex items-center justify-between">
                                    <form method="POST" action="{{ route('verification.send') }}">
                                        @csrf

                                        <div>
                                            <x-primary-button class="btn btn-primary px-4">
                                                {{ __('Resend Verification Email') }}
                                            </x-primary-button>
                                        </div>
                                    </form>
                                    <div class="d-grid gap-2">
                                        <div class="mt-4 flex items-center justify-between">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <div>
                                                    <button type="submit" class="btn btn-dark px-4"><i
                                                            class='bx bx-arrow-back me-1'></i>
                                                        {{ __('Log Out') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end wrapper -->
</body>

</html>
