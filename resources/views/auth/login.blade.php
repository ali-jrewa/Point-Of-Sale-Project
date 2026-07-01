<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>POS - Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Login Page">
    <meta name="author" content="Ali Jriwah">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!--Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" >

    <!--Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" >

    <!--Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" >

    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="login-logo"> <a href="{{ url('/') }}"><b>POS </b> System</a> </div> <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="" method="post">
                    <div class="mb-3 input-group"> <input type="email" class="form-control" placeholder="Email">
                        <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                    </div>
                    <div class="mb-3 input-group"> <input type="password" class="form-control" placeholder="Password">
                        <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                    </div> <!--begin::Row-->
                    <div class="row">
                        <div class="col-8">
                            <div class="form-check"> <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"> <label class="form-check-label" for="flexCheckDefault">
                                    Remember Me
                                </label> </div>
                        </div>
                        <div class="col-4">
                            <div class="gap-2 d-grid"> <button type="submit" class="btn btn-primary">Sign In</button> </div>
                        </div>
                    </div>
                </form>
                <p class="mb-1"> <a href="forgot-password.html">I forgot my password</a> </p>
            </div>
        </div>
    </div>

        <script src="{{ asset('dist/js/adminlte.js') }}"></script>

    </body>

</html>
