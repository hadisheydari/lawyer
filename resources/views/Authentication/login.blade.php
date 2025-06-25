<!DOCTYPE html>
<html lang="fa">

<head>
	<title>بازارگاه </title>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link class="main-css" rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>
<body>
<div class="authincation d-flex flex-column flex-lg-row flex-column-fluid">
    <div class="login-aside text-center d-none d-sm-flex flex-column flex-row-auto">
        <div class="aside-image" style="background-image:url(images/piic2.png);"></div>
    </div>
    <div class="container flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
        <div class="d-flex justify-content-center h-100 align-items-center">
            <div class="authincation-content style-2">
                <div class="row no-gutters">
                    <div class="col-xl-12">
                        <div class="auth-form">
                            <div class="text-center d-block d-sm-none mb-4 pt-5">
                                <a href="index.html"><img src="images/logo-full.png" class="dark-login"  alt=""></a>
                                <a href="index.html"><img src="images/logo-full-white.png" class="light-login" alt=""></a>
                            </div>

                            <h4 class="text-center mb-4">Sign in your account</h4>
                            <form action="index.html">
                                <div class="mb-3">
                                    <label class="mb-1 form-label">Email</label>
                                    <input type="email" class="form-control" value="hello@example.com">
                                </div>
                                <div class="mb-3">
                                    <label class="mb-1 form-label">Password</label>
                                    <div class="position-relative">
                                        <input type="password" id="ic-password" class="form-control" value="Password">
                                        <span class="show-pass eye">
												<i class="fa fa-eye-slash"></i>
												<i class="fa fa-eye"></i>
											</span>
                                    </div>
                                </div>
                                <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                    <div class="mb-3">
                                        <div class="form-check custom-checkbox ms-1">
                                            <input type="checkbox" class="form-check-input" id="basic_checkbox_1">
                                            <label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <a href="page-forgot-password.html">Forgot Password?</a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Sign Me In</button>
                                </div>
                            </form>
                            <div class="new-account mt-3">
                                <p>Don't have an account? <a class="text-primary" href="page-register.html">Sign up</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="{{ asset('vendor/global/global.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/ic-sidenav-init.js') }}"></script>
<script src="{{ asset('js/demo.js') }}"></script>
<script src="{{ asset('js/styleSwitcher.js') }}"></script>

</body>
</html>
