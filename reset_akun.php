<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include "include/koneksi.php";
session_start();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>Reset Akun</title>
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
            <p style="color: black; font-size: 18px;" class="login-box-msg">Reset Akun</p>

            <form method="POST">

                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="phone_number" placeholder="Nomor Telepon Yang Terdaftar">
                    <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" name="reset" class="btn btn-success btn-block btn-flat">Reset</button>
                        <a href="login.php" type="submit" name="reset" class="btn btn-info btn-block btn-flat">Kembali Login</a>
                    </div>
                </div>

            </form>

        </div>
        <!-- /.login-box -->

        <!-- jQuery 2.2.3 -->
        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>

</body>

</html>


<?php

if (isset($_POST['reset'])) {

    $sql_token = "SELECT * FROM tbl_token WHERE id_token = 1";
    $result = mysqli_query($koneksi, $sql_token);
    $row = mysqli_fetch_assoc($result);
    $authorizationToken = $row['token'];

    if (empty($authorizationToken)) {
?>
        <script>
            setTimeout(function() {
                sweetAlert({
                    title: 'Terjadi Masalah',
                    text: 'Maaf, Permintaan Reset Kata Sandi Saat Ini Belum Bisa, Silakan Coba Beberapa Saat Atau Hubungi Admin.',
                    type: 'error'
                });
            }, 300);
        </script>
        <?php
    } else {

        $phone = addslashes(trim($_POST['phone_number']));

        if (substr($phone, 0, 2) === '62' && $phone[2] !== '0') {
            $phone = '0' . substr($phone, 2);
        }

        $sql = $koneksi->query("SELECT * FROM tb_pelanggan INNER JOIN tb_user ON tb_pelanggan.id_pelanggan=tb_user.id_pelanggan WHERE no_telp = '$phone'");
        $data = $sql->fetch_assoc();
        $username = $data['username'];
        $password = $data['password'];
        $ketemu = $sql->num_rows;

        if ($ketemu >= 1) {
            // Lanjutkan dengan proses reset kata sandi dan pengiriman pesan ke API
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $phone,
                    'message' => "*** RESET KATA SANDI ***" . "\n\n" . "Username : " . $username . "\n" . "Password : " . $password . "\n\n" . "Jika Terjadi Kendala dengan Akun Anda Silakan Hubungi Admin",
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $authorizationToken
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            // Tampilkan sweetAlert setelah reset berhasil
        ?>
            <script>
                setTimeout(function() {
                    sweetAlert({
                        title: 'Permintaan Reset Berhasil Dikirim',
                        text: 'Periksa Pesan Whatsapp di Nomor Telepon Yang Terdaftar.',
                        type: 'success'
                    }, function() {
                        window.location = 'login.php';
                    });
                }, 300);
            </script>
        <?php
        } else {
            // Tampilkan sweetAlert jika data tidak ditemukan
        ?>
            <script>
                setTimeout(function() {
                    sweetAlert({
                        title: 'Tidak Terdaftar',
                        text: 'Upss, Sepertinya Data Kamu Tidak Ditemukan.',
                        type: 'error'
                    }, function() {
                        window.location = 'reset_akun.php';
                    });
                }, 300);
            </script>
<?php
        }
    }
}
?>