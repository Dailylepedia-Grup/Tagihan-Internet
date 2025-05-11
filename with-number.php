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

            <form method="POST">

                <div class="form-group has-feedback">
                    <input type="text" class="form-control" autofocus="" name="number" id="number" placeholder="08691234567">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
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
                    <div class="col-xs-12">
                        <button type="submit" name="login" class="btn btn-info btn-block btn-flat">Login</button>
                    </div>
                </div>

                <div class="row" style="padding-top: 10px;">
                    <div class="col-xs-12 ">
                        <a href="login.php" type="button" class="btn btn-success btn-block btn-flat">Login Dengan Akun</a>
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

</body>

</html>


<?php

if (isset($_POST['login'])) {
    $number = addslashes(trim($_POST['number']));

    $sql = $koneksi->query("SELECT * FROM tb_user INNER JOIN tb_pelanggan ON tb_user.id_pelanggan=tb_pelanggan.id_pelanggan WHERE no_telp = '$number'");
    $data = $sql->fetch_assoc();
    $ketemu = $sql->num_rows;

    if ($ketemu >= 1) {
        session_start();
        if ($data['level'] == "admin" || $data['level'] == "user" || $data['level'] == "kasir" || $data['level'] == "teknisi") {
            $_SESSION[$data['level']] = $data['id'];
            header("location: index.php");
            exit;
        } else {
            // Level tidak sesuai
            echo '<script>
                    setTimeout(function() {
                        sweetAlert({
                            title: "Error!",
                            text: "Level tidak sesuai.",
                            type: "error"
                        }, function() {
                            window.location = "with-number.php";
                        });
                    }, 300);
                </script>';
        }
    } else {
        // User tidak ditemukan
        echo '<script>
                setTimeout(function() {
                    sweetAlert({
                        title: "Error!",
                        text: "Nomor Telp Salah, Pastikan Nomor Telepon Anda Terdaftar",
                        type: "error"
                    }, function() {
                        window.location = "with-number.php";
                    });
                }, 300);
            </script>';
    }
}
?>