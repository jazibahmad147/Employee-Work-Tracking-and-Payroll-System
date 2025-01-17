<!DOCTYPE html>
<html lang="de">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HB Gebaeude</title>

    <!-- Bootstrap -->
    <link href={{asset('vendors/bootstrap/dist/css/bootstrap.min.css')}} rel="stylesheet">
    <!-- Font Awesome -->
    <link href={{asset('vendors/font-awesome/css/font-awesome.min.css')}} rel="stylesheet">
    <!-- NProgress -->
    <link href={{asset('vendors/nprogress/nprogress.css')}} rel="stylesheet">
    <!-- Animate.css -->
    <link href={{asset('vendors/animate.css/animate.min.css')}} rel="stylesheet">
    <!-- sweetalerts -->
    <script src="{{asset('vendors/sweetalerts/sweetalerts.min.js')}}"></script>

    <!-- Custom Theme Style -->
    <link href={{asset('build/css/custom.min.css')}} rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form class="user" action="/login" method="POST">
                @csrf
              <h1>Login Form</h1>
              <div>
                <input type="email" class="form-control" name="email" placeholder="Email" required />
              </div>
              <div>
                <input type="password" class="form-control" name="password" placeholder="Password" required />
              </div>
              <div>
                <input type="submit" class="btn btn-default">
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">New to site?
                  <a href="#signup" class="to_register"> Create Account </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1>Attendance Management</h1>
                  <p>Copyright © Nasir High Secondary School Attendance Management 2023 | By Jazib Ahmad</p>
                </div>
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form class="user" action="/register" method="POST">
              @csrf
              <h1>Create Account</h1>
              <div>
                <input type="text" class="form-control" name="fname" placeholder="Full Name" required />
              </div>
              <div>
                <input type="email" class="form-control" name="email" placeholder="Email" required />
              </div>
              <div>
                <input type="password" class="form-control" name="password" placeholder="Password" required />
              </div>
              <div>
                <input type="submit" class="btn btn-default submit">
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="#signin" class="to_register"> Log in </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1>HB Gebaeude</h1>
                  <p>Copyright © HB Gebaeude 2024 | By Jazib Ahmad.</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
    <!-- jQuery -->
    <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('build/js/user.js')}}"></script>
  </body>
</html>
