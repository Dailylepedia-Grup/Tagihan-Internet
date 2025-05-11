<?php
include("../../include/koneksi.php");
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

        <?php
        $sql = $koneksi->query("SELECT * FROM tb_profile");
        $data = $sql->fetch_assoc();
        ?>

        <tr>
            <td width="0">&nbsp;</td>
            <td colspan="6">
                <div align="center"><strong>Riwayat Pendapatan Setiap Bulan <br><?= $data['nama_sekolah'] ?></strong></div>
            </td>
        </tr>

    </table><br>

    </table>
    <br>

    <table class="tabel" border="1" width="100%">

        <thead>
            <tr>
                <th>No</th>
                <th>Tahun/Bulan</th>
                <th>Transaksi</th>
                <th>Pemasukan</th>
                <th>Pengeluaran</th>
                <th>Pendapatan Bersih</th>
            </tr>
        </thead>

        <tbody>

            <?php
            $i = 1;
            //direct admin
            // $queryBulan = $koneksi->query("SELECT DISTINCT DATE_FORMAT(tgl_kas, '%Y-%m') AS bulan_tahun FROM tb_kas ORDER BY bulan_tahun DESC");
            // cpanel
            $queryBulan = $koneksi->query("SELECT DISTINCT DATE_FORMAT(tgl_kas, '%Y-%m') AS bulan_tahun FROM tb_kas ORDER BY tgl_kas DESC");
            while ($row = $queryBulan->fetch_assoc()) {
                $bulan_tahun = $row['bulan_tahun'];
                $queryTransaksi = $koneksi->query("SELECT COUNT(*) AS jumlah_transaksi, SUM(penerimaan) AS total_penerimaan, SUM(pengeluaran) AS total_pengeluaran FROM tb_kas WHERE DATE_FORMAT(tgl_kas, '%Y-%m') = '$bulan_tahun'");
                $data = $queryTransaksi->fetch_assoc();

                // Hitung total pendapatan bersih
                $total_pendapatan_bersih = $data['total_penerimaan'] - $data['total_pengeluaran'];
            ?>
                <tr>
                    <td align="center" valign="middle"><?= $i++; ?></td>
                    <td align="center" valign="middle"><?= $bulan_tahun ?></td>
                    <td align="center" valign="middle"><?= $data['jumlah_transaksi'] ?></td>
                    <td align="center" valign="middle"><?= 'Rp ' . number_format($data['total_penerimaan'], 0, ',', '.') ?></td>
                    <td align="center" valign="middle"><?= 'Rp ' . number_format($data['total_pengeluaran'], 0, ',', '.') ?></td>
                    <td align="center" valign="middle"><?= 'Rp ' . number_format($total_pendapatan_bersih, 0, ',', '.') ?></td>
                </tr>
            <?php
            }
            ?>

        </tbody>

    </table>