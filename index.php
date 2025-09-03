<?php
// If session is now active (user is logged in), redirect them
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'user') {
        header("Location: ./pages/forms/userHomepage.php");
        exit;
    } elseif ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager') {
        header("Location: ./pages/forms/advanced.php");
        exit;
    } else {
        // fallback redirect if role is unknown
        header("Location: ./pages/forms/dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ChatBot | Log in</title>

    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
    />
    <link
      rel="stylesheet"
      href="../../plugins/fontawesome-free/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css"
    />
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css" />
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="../../index2.html">ChatBot</a>
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Sign in to start your session</p>

          <form id="login-form">
            <div class="input-group mb-3">
              <input
                type="email"
                class="form-control"
                id="email"
                placeholder="Email"
                required
              />
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input
                type="password"
                class="form-control"
                id="password"
                placeholder="Password"
                required
              />
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">
                  Sign In
                </button>
              </div>
            </div>
          </form>

          <p class="mb-1">
            <a href="/pages/forms/authentication/forgotPassword.html"
              >I forgot my password</a
            >
          </p>
          <p class="mb-0">
            <a
              href="/pages/forms/authentication/register.html"
              class="text-center"
              >Register a new membership</a
            >
          </p>
        </div>
      </div>
    </div>

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>

    <script>
      $(document).ready(function () {
        $("#login-form").on("submit", function (event) {
          event.preventDefault(); // Prevent the form from submitting normally

          let email = $("#email").val();
          let password = $("#password").val();

          // Send AJAX request to login.php
          $.ajax({
            url: "./api/login.php",
            type: "POST",
            data: { email: email, password: password },
            dataType: "json",
            success: function (response) {
              if (response.success) {
                if (response.role === "user") {
                  window.location.href = "../pages/forms/userHomepage.php";
                } else if (
                  response.role === "admin" ||
                  response.role === "manager"
                ) {
                  window.location.href = "../pages/forms/advanced.php";
                } else {
                  alert("Unknown user role");
                }
              } else {
                alert(response.message);
              }
            },
            error: function (xhr, status, error) {
              alert("An error occurred: " + error);
            },
          });
        });
      });
    </script>
  </body>
</html>
