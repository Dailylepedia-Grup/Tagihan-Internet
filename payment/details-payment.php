<?php

include("../include/koneksi.php");

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['id_tagihan']) || !isset($_POST['nama_pelanggan'])) {
    die("Error: Data Tidak Ditemukan.");
}

$id_tagihan = $_POST['id_tagihan'];
$nama_pelanggan = $_POST['nama_pelanggan'];

$sql = $koneksi->query("SELECT * FROM tb_tagihan 
                       INNER JOIN tb_pelanggan ON tb_tagihan.id_pelanggan = tb_pelanggan.id_pelanggan 
                       INNER JOIN tb_paket ON tb_pelanggan.paket = tb_paket.id_paket
                       INNER JOIN tb_user ON tb_pelanggan.id_pelanggan = tb_user.id_pelanggan
                       WHERE tb_tagihan.id_tagihan = '$id_tagihan'
                       AND tb_pelanggan.nama_pelanggan = '$nama_pelanggan'");

if (!$sql) {
    die("Query failed: " . $koneksi->error);
}

if ($sql->num_rows == 0) {
    die("Error: Data Tidak Ditemukan.");
}

$payment = $sql->fetch_assoc();

$sqll = $koneksi->query("SELECT * FROM tbl_badmin WHERE id_badmin=1");
$bAdmin = $sqll->fetch_assoc();

$conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
$checkUser = $conPelanggan->fetch_assoc();

if ($checkUser['ippelanggan'] == 'statik') {
    $ip = $payment['ip_address'];
} else {
    $ip = $payment['username'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .payment-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 90%;
            max-width: 400px;
            margin: auto;
            margin-top: 20px;
        }

        .payment-header {
            background-color: #007bff;
            color: #fff;
            padding: 5px;
            text-align: center;
        }

        .payment-content {
            padding: 20px;
            text-align: center;
        }

        .info-item {
            margin-bottom: 15px;
            text-align: left;
        }

        .info-item strong {
            display: block;
            margin-bottom: 5px;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 45%;
            margin-right: 5%;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="payment-container">
        <div class="payment-header">
            <h3>Detail Pembayaran</h3>
        </div>
        <div class="payment-content">
            Lakukan Pembayaran Otomatis
            <hr>
            <form action="metode-pembayaran.php" method="POST">
                <div class="info-item">
                    <input type="hidden" name="id_tagihan" value="<?= $payment['id_tagihan'] ?>">
                    <input type="hidden" name="ip" value="<?= $ip ?>">
                    <strong>Nama</strong> <?= $payment['nama_pelanggan'] ?>
                    <input type="hidden" name="name_user" value="<?= $payment['nama_pelanggan'] ?>">
                    <input type="hidden" name="nama_paket" value="<?= $payment['nama_paket'] ?>">
                    <input type="hidden" name="no_telp" value="<?= $payment['no_telp'] ?>">
                    <hr>
                </div>
                <div class="info-item">
                    <strong>Paket</strong> <?= $payment['nama_paket'] ?>
                    <input type="hidden" name="name_paket" value="<?= $payment['nama_paket'] ?>">
                    <hr>
                </div>
                <div class="info-item">
                    <?php
                    $bulanTahun = $payment['bulan_tahun'];
                    $bulan = substr($bulanTahun, 0, 2);
                    $tahun = substr($bulanTahun, 2);
                    $formattedDate = $bulan . ' - ' . $tahun;
                    ?>
                    <strong>Pembayaran Bulan</strong> <?= $formattedDate ?>
                    <hr>
                </div>
                <?php if (!empty($bAdmin['harga'] && $bAdmin['status'] == 'pelanggan')) { ?>
                    <div class="info-item">
                        <?php
                        $g = $bAdmin['harga'];
                        $hargaAdmin = number_format($g, 0, ',', '.');
                        ?>
                        <strong>Biaya Admin</strong> Rp.<?= $hargaAdmin ?>
                        <hr>
                    </div>
                <?php } ?>
                <div class="info-item">
                    <?php

                    $getData = $koneksi->query("SELECT * FROM tbl_paketmikrotik");
                    $data1 = $getData->fetch_assoc();

                    if ($data1['ppn'] == 'aktif') {
                        $ppn = $payment['harga'] * $payment['ppn'];
                        $totalSemua = $payment['harga'] + $ppn + $g;

                        $formattedTotalTagihan = 'Rp ' . number_format($totalSemua, 0, ',', '.');
                    } else {
                        $totalSemua = $payment['harga'] + $g;
                        $formattedTotalTagihan = 'Rp ' . number_format($totalSemua, 0, ',', '.');
                    }
                    ?>
                    <strong>Total Tagihan</strong> <?= $formattedTotalTagihan ?>
                    <input type="hidden" name="harga" value="<?= $totalSemua ?>">
                </div>
                <hr>
                <button id="pay-button" type="submit">Metode Pembayaran</button>
            </form>
        </div>
    </div>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>