<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "../../include/koneksi.php";
$bulan = $_GET['bulan'];
$tahun = $_GET['tahun'];

$bulantahun = $bulan . $tahun;

$satu_hari        = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

function tglIndonesia2($str)
{
    $tr   = trim($str);
    $str    = str_replace(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'), array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'), $tr);
    return $str;
}

?>

<style type="text/css">
    .tabel {
        border-collapse: collapse;
    }

    .tabel th {
        padding: 8px 5px;
        background-color: #cccccc;
    }

    .tabel td {
        padding: 8px 5px;
    }
</style>
<script>
    window.print();
    window.onfocus = function() {
        window.close();
    }
</script>
</head>

<body onload="window.print()">

    <table width="100%">
        <tr>
            <td width="0">&nbsp;</td>
            <td colspan="6">
                <div align="center"><strong>Data Tagihan <br> Bulan <?php echo  $bulan ?> Tahun <?php echo  $tahun ?> </strong></div>
            </td>
        </tr>

    </table><br>

    </table>
    <br>


    <table class="tabel" border="1" width="100%">

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Nama Paket</th>
                <th>Bulan/Tahun</th>
                <th>Tagihan</th>
                <th>Status Bayar</th>
            </tr>
        </thead>
        <tbody>

            <?php

            $no = 1;
            $sql = $koneksi->query("select tb_tagihan.*, tb_pelanggan.nama_pelanggan, tb_pelanggan.alamat,   tb_paket.nama_paket, tb_pelanggan.no_telp
                          from tb_tagihan
                          inner join tb_pelanggan on tb_tagihan.id_pelanggan=tb_pelanggan.id_pelanggan
                          inner join tb_paket on tb_pelanggan.paket=tb_paket.id_paket
                          where tb_tagihan.bulan_tahun='$bulantahun'  order by status_bayar asc
                        ");

            while ($data = $sql->fetch_assoc()) {

                $status = $data['status_bayar'];

                if ($status == 0) {
                    $status_t = "Belum Lunas";
                    $color = "red";
                } else {
                    $status_t = "Lunas";
                    $color = "green";
                }

            ?>

                <tr>
                    <td style="color: <?php echo $color ?>"><?php echo $no++; ?></td>
                    <td style="color: <?php echo $color ?>"><?php echo $data['nama_pelanggan'] ?></td>
                    <td style="color: <?php echo $color ?>"><?php echo $data['nama_paket'] ?></td>
                    <td style="color: <?php echo $color ?>"><?php echo $bulan ?> / <?php echo $tahun ?></td>
                    <td style="color: <?php echo $color ?>"><?php echo number_format($data['jml_bayar'], 0, ",", ".") ?></td>
                    <td style="color: <?php echo $color ?>"><?php echo $status_t; ?></td>
                </tr>

            <?php

            }

            ?>

        </tbody>
    </table>