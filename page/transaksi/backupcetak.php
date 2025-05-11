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
<style type="text/css">
    .tabel {
        border-collapse: collapse;
    }

    .tabel th {
        padding: 8px 5px;
        background-color: #3b4e87;
    }

    .tabel td {
        padding: 8px 5px;
    }

    .tabel2 {
        border-collapse: collapse;
    }

    .tabel2 th {
        padding: 7px 4px;
        color: white;
        background-color: #3b4e87;
    }

    .tabel2 td {
        padding: 2px 3px;
    }

    .tabel3 {
        border-collapse: collapse;
    }

    .tabel3 th {
        padding: 8px 5px;
        color: white;
        background-color: #3b4e87;
    }

    .tabel3 td {
        padding: 5px 3px;
    }

    .isi {
        width: 100%;
    }

    .isi .kiri {
        float: left;
        width: 50%;
        text-align: justify;
    }

    .kiri span {
        font-size: 16px;
        font-weight: bold;
    }

    .isi .kanan {
        float: right;
        width: 45%;

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

    <div class="isi">
        <div class="kiri">
            <img src="../../images/<?php echo $data1['foto'] ?>" width="150" height="75"><br><br>
            <b>
                <?php echo $data1['nama_sekolah'] ?>

            </b><br>

            <b>
                <?php echo $data1['alamat'] ?>

            </b>
        </div>

        <div class="kanan">
            <br><br> <br><br>
            <table width="321" border="1" class="tabel2">
                <tr>
                    <th colspan="2" align="center">Bukti Pembayaran</th>
                </tr>
                <tr>
                    <td width="135">Tanggal Bayar</td>
                    <td width="185">
                        <?php if ($data2['status_bayar'] == 1) {
                            echo date('d-m-Y', strtotime($data2['tgl_bayar']));
                        } else {
                            echo "Tanggal Tidak Ditemukan";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>NO. INVOICE</td>
                    <td>
                        <?php if ($data2['status_bayar'] == 1) {
                            echo $data2['no_invoice'];
                            echo $data2['bulan_tahun'];
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Status Pembayaran</td>
                    <td>
                        <?php if ($data2['status_bayar'] == 1) {
                            echo "Lunas";
                        } else {
                            echo "Belum Lunas";
                        }
                        ?>
                    </td>
                </tr>

            </table>
        </div>
    </div>

</body>

<br><br><br><br><br><br><br><br><br><br><br>

<table width="321" border="1" class="tabel2">
    <tr>
        <th colspan="2" align="center">Kepada</th>
    </tr>
    <tr>
        <td><?php echo $data2['nama_pelanggan'] ?> <br> <?php echo $data2['alamat'] ?></td>
    </tr>

</table> <br><br>


<table width="100%" border="1" class="tabel3">
    <tr>
        <th width="140" height="40">
            <div align="center">Keterangan</div>
        </th>
        <th width="89">
            <div align="center">Pembayaran</div>
        </th>

        <th width="45">
            <div align="center">QTY</div>
        </th>
        <th width="102">
            <div align="center">Total</div>
        </th>
    </tr>
    <tr>
        <td height="40"><?php echo $data2['nama_paket'] ?></td>
        <td align="right">Rp. <?php echo number_format($data2['jml_bayar'], 0, ",", ".") ?></td>

        <td align="center">1</td>
        <td align="right">Rp. <?php echo number_format($data2['jml_bayar'], 0, ",", ".") ?></td>
    </tr>
    <tr>
        <td colspan="3">
            <div align="right">Subtotal</div>
        </td>
        <td align="right">Rp. <?php echo number_format($data2['jml_bayar'], 0, ",", ".") ?></td>
    </tr>
    <tr>
        <td colspan="3">
            <div align="right">Administrasi</div>
        </td>
        <td align="right">Rp. -</td>
    </tr>
    <tr>
        <td colspan="3">
            <div align="right">Discount</div>
        </td>
        <td align="right">Rp. -</td>
    </tr>
    <tr>
        <td colspan="3">
            <div align="right">PPN</div>
        </td>
        <td align="right">Rp. - </td>
    </tr>
    <tr>
        <td colspan="3">
            <div align="right">TOTAL</div>
        </td>
        <td align="right">Rp. <?php echo number_format($total_bayar, 0, ",", ".") ?></td>
    </tr>
</table><br><br>



<table width="321" border="1" class="tabel2">
    <tr>
        <th colspan="2" align="center">CATATAN</th>
    </tr>
    <tr>
        <td align="justify">
            Terima Kasih Telah Melakukan Pembayaran, Simpanlah Struk Ini Sebagai Bukti Pembayaran
        </td>
    </tr>
</table>