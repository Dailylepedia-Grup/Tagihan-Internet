<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "../../include/koneksi.php";

$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];
$s = $_GET['status_bayar'];

$bulantahun = $bulan . $tahun;

$satu_hari = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

function tglIndonesia2($str)
{
    $tr   = trim($str);
    $str    = str_replace(
        array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
        array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'),
        $tr
    );
    return $str;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Pembayaran</title>
    <style>
        #tabel {
            font-size: 15px;
            border-collapse: collapse;
        }

        #tabel td {
            padding: 5px;
            border: 1px solid black;
        }
    </style>
    <script>
        window.print();
        window.onfocus = function() {
            window.close();
        }
    </script>
</head>

<body style="font-family: Tahoma; font-size: 8pt;" onload="window.print()">

    <?php

    $sql = $koneksi->query("SELECT * FROM tb_profile ");

    $data1 = $sql->fetch_assoc();
    $sql2 = $koneksi->query("SELECT tb_tagihan.*, tb_pelanggan.nama_pelanggan, tb_pelanggan.alamat, tb_paket.nama_paket, tb_paket.harga, tb_paket.ppn, tb_pelanggan.no_telp, tb_pelanggan.kode_pelanggan
    FROM tb_tagihan
    INNER JOIN tb_pelanggan ON tb_tagihan.id_pelanggan=tb_pelanggan.id_pelanggan
    INNER JOIN tb_paket ON tb_pelanggan.paket=tb_paket.id_paket
    WHERE SUBSTRING(tb_tagihan.bulan_tahun, 1, 2) = '$bulan' AND SUBSTRING(tb_tagihan.bulan_tahun, 3, 4) = '$tahun' AND tb_tagihan.status_bayar IS NULL
  ");

    while ($data2 = $sql2->fetch_assoc()) {

        $admin = 0;

        $ppn = 0;

        $total_bayar = $data2['jml_bayar'] + $admin + $ppn;

        $bulan_tahun = $data2['bulan_tahun'];

        $tahun  = str_split($bulan_tahun);

        $tahun1 = $tahun[0];
        $tahun2 = $tahun[1];
        $tahun3 = $tahun[2];
        $tahun4 = $tahun[3];
        $tahun5 = $tahun[4];
        $tahun6 = $tahun[5];

        $bulan = $tahun1 . $tahun2;

        $tahun = $tahun3 . $tahun4 . $tahun5 . $tahun6;

    ?>

        <center>

            <table style="width: 550px; font-size: 8pt; font-family: Calibri; border-collapse: collapse;" border="0">
                <tr>
                    <td rowspan="2" style="padding-right: 10px;">
                        <img src="../../images/<?php echo $data1['foto'] ?>" width="50" height="50">
                    </td>
                </tr>

                <tr>
                    <td width="70%" align="left" style="padding-right: 80px; vertical-align: top">
                        <span style="font-size: 12pt;"><b><?php echo $data1['nama_sekolah'] ?></b></span><br>
                        <?php echo $data1['alamat'] ?>
                    </td>
                    <td style="vertical-align: top" width="30%" align="left">
                        <b><span style="font-size: 12pt;">Bukti Pembayaran</span></b><br>
                        Invoice. : <?php if ($data2['status_bayar'] == 1) {
                                        echo $data2['no_invoice'];
                                    } else {
                                        echo "-";
                                    }
                                    ?><br>
                        Tanggal Bayar : <?php if ($data2['status_bayar'] == 1) {
                                            echo date('d-m-Y', strtotime($data2['tgl_bayar']));
                                        } else {
                                            echo "-";
                                        }
                                        ?><br>
                    </td>
                </tr>
            </table>
            <table style="width: 550px; font-size: 8pt; font-family: Calibri; border-collapse: collapse;" border="0">
                <tr>
                    <td width="70%" align="left" style="padding-right: 80px; vertical-align: top">
                        Kode Pelanggan : <?= $data2['kode_pelanggan'] ?> <br>
                        Nama Pelanggan : <?php echo $data2['nama_pelanggan'] ?><br>
                        Alamat :<?php echo $data2['alamat'] ?>
                    </td>
                    <td style="vertical-align: top" width="30%" align="left">
                        Status : <?php if ($data2['status_bayar'] == 1) {
                                        echo "Lunas";
                                    } else {
                                        echo "Belum Lunas";
                                    }
                                    ?> <br>
                        No Telp : <?php echo $data2['no_telp'] ?>
                    </td>
                </tr>
            </table>
            <table cellspacing="0" style="width: 550px; font-size: 8pt; font-family: Calibri;  border-collapse: collapse;" border="1">
                <tr align="center">

                    <td width="20%">PAKET</td>
                    <td width="13%">Harga</td>
                    <td width="13%">Total Harga</td>
                </tr>
                <tr>

                    <td style="text-align:center"><?php echo $data2['nama_paket'] ?></td>
                    <td style="text-align:center">Rp. <?php echo number_format($data2['harga'], 0, ",", ".") ?></td>
                    <?php
                    $getData = $koneksi->query("SELECT * FROM tbl_paketmikrotik");
                    $data3 = $getData->fetch_assoc();

                    if ($data3['ppn'] == 'aktif') {
                        // Ganti 'ppn' dengan nama kolom yang sesuai pada tabel Anda
                        $nilai_ppn_database = $data2['ppn'];

                        // Ubah nilai dari database ke bentuk persentase jika tidak NULL
                        $nilai_ppn_tampilan = ($nilai_ppn_database !== NULL) ? $nilai_ppn_database * 100 : NULL;
                        $fix = ($nilai_ppn_tampilan !== NULL) ? $nilai_ppn_tampilan . " %" : "";
                    } else {
                        $fix = "0%";
                    }
                    ?>
                    <td style="text-align:center">Rp. <?php echo number_format($data2['harga'], 0, ",", ".") ?> + PPN <?= $fix ?> </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div style="text-align:right">Total : </div>
                    </td>
                    <td style="text-align:center">Rp. <?php echo number_format($data2['jml_bayar'], 0, ",", ".") ?></td>
                </tr>

                <?php
                $getData = $koneksi->query("SELECT * FROM tbl_paketmikrotik");
                $data3 = $getData->fetch_assoc();

                if ($data3['ppn'] == 'aktif') {
                    // Ganti 'ppn' dengan nama kolom yang sesuai pada tabel Anda
                    $nilai_ppn_database = $data2['ppn'];

                    // Ubah nilai dari database ke bentuk persentase jika tidak NULL
                    $nilai_ppn_tampilan = ($nilai_ppn_database !== NULL) ? $nilai_ppn_database * 100 : NULL;
                    $fix = ($nilai_ppn_tampilan !== NULL) ? $nilai_ppn_tampilan . " %" : "";
                } else {
                    $fix = "0%";
                }

                ?>

                <tr>
                    <td colspan="2">
                        <div style="text-align:right">PPN : </div>
                    </td>
                    <td style="text-align:center"><?= $fix ?></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div style="text-align:right">Total Yang Harus Di Bayar Adalah : </div>
                    </td>
                    <td style="text-align:center">Rp. <?php echo number_format($total_bayar, 0, ",", ".") ?></td>
                </tr>

            </table>
            <table style="width: 650; font-size: 7pt;" cellspacing="2">
                <tr>
                    <td>
                        <span>
                            <small style="color: red;">Bukti Pembayaran Ini Merupakan Bukti yang sah dari <?php echo $data1['nama_sekolah'] ?></small>
                        </span>
                    </td>
                </tr>
            </table>
            ==================== Potong Disini ====================
        </center>
    <?php } ?>
</body>

</html>