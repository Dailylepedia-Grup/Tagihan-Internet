<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "../../include/koneksi.php";
$id_tagihan = $_GET['id_tagihan'];

$satu_hari        = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

function tglIndonesia($str)
{
    $tr   = trim($str);
    $str    = str_replace(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'), array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'), $tr);
    return $str;
}

?>

<!DOCTYPE html>
<html lang="en">

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
</head>

<body style="font-family: Tahoma; font-size: 8pt;">

    <?php

    $sql = $koneksi->query("select * from tb_profile ");

    $data1 = $sql->fetch_assoc();

    $sql2 = $koneksi->query("select tb_tagihan.*, tb_pelanggan.nama_pelanggan, tb_pelanggan.alamat,   tb_paket.nama_paket, tb_pelanggan.no_telp
                          from tb_tagihan
                          inner join tb_pelanggan on tb_tagihan.id_pelanggan=tb_pelanggan.id_pelanggan
                          inner join tb_paket on tb_pelanggan.paket=tb_paket.id_paket
                          where tb_tagihan.id_tagihan='$id_tagihan' 
                        ");


    $data2 = $sql2->fetch_assoc();

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
                    Invoice. : 4<br>
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
                    Nama Pelanggan : <?php echo $data2['nama_pelanggan'] ?><br>
                    Alamat :<?php echo $data2['alamat'] ?>
                </td>
                <td style="vertical-align: top" width="30%" align="left">
                    Status : Lunas <br>
                    No Telp : -
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
                <td style="text-align:center">Rp. <?php echo number_format($data2['jml_bayar'], 0, ",", ".") ?></td>
                <td style="text-align:right">Rp. <?php echo number_format($data2['jml_bayar'], 0, ",", ".") ?></td>
            </tr>

            <tr>
                <td colspan="2">
                    <div style="text-align:right">Total : </div>
                </td>
                <td style="text-align:right">Rp0,00</td>
            </tr>

            <tr>
                <td colspan="2">
                    <div style="text-align:right">Total Yang Harus Di Bayar Adalah : </div>
                </td>
                <td style="text-align:right">Rp. <?php echo number_format($total_bayar, 0, ",", ".") ?></td>
            </tr>

        </table>
        <table style="width: 650; font-size: 7pt;" cellspacing="2">
            <tr>
                <td>
                    <span>
                        <small style="color: red;">*Harap Simpan Baik - Baik Bukti Pembayaran ini</small>
                    </span>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>