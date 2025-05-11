<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include "include/koneksi.php";
session_start();

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">

  <title>Halaman Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <link rel="stylesheet" type="text/css" href="sw/dist/sweetalert.css">
  <script type="text/javascript" src="sw/dist/sweetalert.min.js"></script>

  <style>
    body {
      background: url('images/photo2.png') no-repeat center fixed;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;

    }
  </style>

</head>

<body class="hold-transition login-page  login-img">
  <div class="login-box">
    <div class="login-logo">

    </div>

    <?php

    $sql2 = $koneksi->query("select * from tb_profile ");

    $data1 = $sql2->fetch_assoc();

    ?>

    <!-- /.login-logo -->
    <div class="login-box-body">
      <h3 style=" text-align: center; "> <img src="images/<?php echo $data1['foto'] ?>" width="90" height="80" alt=""></h3>

      <h3 style="color: black; font-size: 17px;  text-align: center;"> <b><?php echo $data1['nama_sekolah'] ?></b></h3>
      <p style="color: black; font-size: 18px;" class="login-box-msg">Halaman Login</p>

      <form method="POST" id="loginForm" onsubmit="onLoginFormSubmitted()">

        <div class="form-group has-feedback">
          <input type="text" class="form-control" autofocus="" name="username" id="username" placeholder="Username" value="<?php echo isset($_COOKIE['remember_username']) ? $_COOKIE['remember_username'] : ''; ?>">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo isset($_COOKIE['remember_password']) ? $_COOKIE['remember_password'] : ''; ?>">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="row">

          <div class="col-xs-8">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="remember" checked> Ingat Saya
              </label>
            </div>
          </div>

          <div class="col-xs-4">
            <div class="checkbox">
              <a href="reset_akun.php">Lupa Akun?</a>
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-xs-12 ">
            <button type="submit" name="login" class="btn btn-info btn-block btn-flat">Login</button>
          </div>
        </div>

        <div class="row" style="padding-top: 10px;">
          <div class="col-xs-12">
            <a href="with-number.php" type="button" name="login" class="btn btn-success btn-block btn-flat">Login Dengan Nomor Telepon</a>
          </div>
        </div>

      </form>


      <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 2.2.3 -->
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <script>
      function onLoginFormSubmitted() {
        // Mendapatkan nilai username dan password dari form
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        // Memanggil fungsi Android untuk mengirim hasil inputan form login
        Android.onLoginFormSubmitted(username, password);
      }
    </script>

</body>

</html>


<?php

if (isset($_POST['login'])) {

  $username = addslashes(trim($_POST['username']));
  $password = addslashes(trim($_POST['password']));

  // Check if "Remember Me" is selected
  if (isset($_POST['remember'])) {
    // Set cookies with the username and password
    setcookie('remember_username', $username, time() + (60 * 60 * 24 * 365));
    setcookie('remember_password', $password, time() + (60 * 60 * 24 * 365));
  } else {
    // If "Remember Me" is not selected, clear the cookies
    setcookie('remember_username', '', time() - 3600);
    setcookie('remember_password', '', time() - 3600);
  }

  $sql = $koneksi->query("SELECT * FROM tb_user WHERE username='$username' AND password='$password'");
  $data = $sql->fetch_assoc();
  $ketemu = $sql->num_rows;

  if ($ketemu >= 1) {
    session_start();
    if ($data['level'] == "admin" || $data['level'] == "user" || $data['level'] == "kasir" || $data['level'] == "teknisi") {
      $_SESSION[$data['level']] = $data['id'];
      header("location: index.php");
      exit;
    }
  } else {

?>
    <script>
      setTimeout(function() {
        sweetAlert({
          title: 'Username dan Password Salah!',
          text: 'Silahkan Masukan Username dan Password Yang Benar!',
          type: 'error'
        }, function() {
          window.location = 'login.php';
        });
      }, 300);
    </script>

<?php
  }
}
?>