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
                <div align="center"><strong>Data Keseluruhan Jumlah Pelanggan <br><?= $data['nama_sekolah'] ?></strong> </strong></div>
            </td>
        </tr>

    </table><br>

    </table>
    <br>

    <table class="tabel" border="1" width="100%">

        <?php
        $getData = $koneksi->query("SELECT * FROM tbl_paketmikrotik");
        $data3 = $getData->fetch_assoc();
        ?>

        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>NIK KTP</th>
                <th>Nama Paket</th>
                <th>Alamat</th>
                <?php if ($data3['ppn'] == 'aktif') { ?>
                    <th>PPN</th>
                <?php } ?>
                <th>Harga</th>
                <th>Telepon</th>
            </tr>
        </thead>
        <tbody>

            <?php

            $no = 1;
            $Cpelanggan = $koneksi->query("SELECT * FROM tb_pelanggan INNER JOIN tb_paket ON tb_pelanggan.paket = tb_paket.id_paket");
            $jumlah_pelanggan = $Cpelanggan->num_rows;

            while ($cetak = $Cpelanggan->fetch_assoc()) {

            ?>

                <tr>
                    <td style="color: <?php echo $color ?>" align="center" valign="middle"><?php echo $no++; ?></td>
                    <td style="color: <?php echo $color ?>" align="center" valign="middle"><?php echo $cetak['nama_pelanggan'] ?></td>
                    <td style="color: <?php echo $color ?>" align="center" valign="middle">
                        <?php echo !empty($cetak['nik']) ? $cetak['nik'] : '-'; ?>
                    </td>
                    <td style="color: <?php echo $color ?>" align="center" valign="middle"><?php echo $cetak['nama_paket'] ?></td>
                    <td style="color: <?php echo $color ?>" align="center" valign="middle"><?php echo $cetak['alamat'] ?></td>

                    <?php if ($data3['ppn'] == 'aktif') { ?>
                        <?php
                        // Ganti 'ppn' dengan nama kolom yang sesuai pada tabel Anda
                        $nilai_ppn_database = $cetak['ppn'];

                        // Ubah nilai dari database ke bentuk persentase jika tidak NULL
                        $nilai_ppn_tampilan = ($nilai_ppn_database !== NULL) ? $nilai_ppn_database * 100 : NULL;
                        $fix = ($nilai_ppn_tampilan !== NULL) ? $nilai_ppn_tampilan . "%" : "";

                        // Perbarui cara menetapkan nilai ke atribut 'value'
                        ?>
                        <td style="color: <?php echo $color ?>" align="center" valign="middle"><?php echo $fix ?></td>
                    <?php } ?>

                    <td style="color: <?php echo $color ?>" align="center" valign="middle">

                        <?php
                        if ($data3['ppn'] == 'aktif') {
                            $ppn = $cetak['harga'] * $cetak['ppn'];
                            $total_harga = $cetak['harga'] + $ppn;
                        } else {
                            $total_harga = $cetak['harga'];
                        }
                        echo "Rp." . number_format($total_harga, 0, ",", ".");
                        ?>

                    </td>

                    <td style="color: <?php echo $color ?>" align="center" valign="middle"><?php echo $cetak['no_telp'] ?></td>

                </tr>

            <?php

            }

            ?>

        </tbody>
    </table>