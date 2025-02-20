<!doctype html>
<html lang="en">

<head>
    @include('admin.body.head')
    <title>Register - HR Management</title>
</head>

<body class="">
<!--wrapper-->
<div class="wrapper">
    <div class="section-authentication-cover">
        <div class="">
            <div class="row g-0">

                <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                    <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                        <div class="card-body">
                            <img src="{{asset('assets/images/login-images/register-cover.svg')}}" class="img-fluid auth-img-cover-login" width="550" alt=""/>
                        </div>
                    </div>

                </div>

                <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                    <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                        <div class="card-body p-sm-5">
                            <div class="">
                                <div class="mb-3 text-center">
                                    <img src="{{asset('assets/images/logo-icon.png')}}" width="60" alt="" />
                                </div>
                                <div class="text-center mb-4">
                                    <h5 class="">HR Management | Register</h5>
                                    <p class="mb-0">Please fill the below details to create your account</p>
                                </div>
                                <div class="form-body">
                                    <form class="row g-3" method="POST" action="{{route('register')}}">
                                        @csrf
                                        <div class="col-12">
                                            <label for="inputUsername" class="form-label">Full Name</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="Full Name">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputUsername" class="form-label">User Name</label>
                                            <input name="username" type="text" class="form-control" id="username" placeholder="User Name">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputEmailAddress" class="form-label">Email Address</label>
                                            <input name="email" type="email" class="form-control" id="email" placeholder="example@user.com">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">Password</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input name="password" type="password" class="form-control border-end-0" id="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">Confirm Password</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input name="password_confirmation" type="password" class="form-control border-end-0" id="password_confirmation" placeholder="Condirm Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Register</button>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="text-center ">
                                                <p class="mb-0">Already have an account? <a href="{{route('login')}}">Sign in here</a></p>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--end row-->
        </div>
    </div>
</div>
<!--end wrapper-->
<!-- Bootstrap JS -->
<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<!--plugins-->
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
<script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<!--Password show & hide js -->
<script>
    $(document).ready(function () {
        $("#show_hide_password a").on('click', function (event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
    });
</script>
<!--app JS-->
<script src="{{asset('assets/js/app.js')}}"></script>
</body>

</html>
