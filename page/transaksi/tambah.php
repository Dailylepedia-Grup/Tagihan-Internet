<form method="POST">
    <div class="col-md-12">

        <div class="col-md-3">
            <div class="form-group">

                <br><label style="font-weight: bold;">Bulan</label> <br>
                <select required="" class="form-control" name="bulan">

                    <option value="">--Pilih Bulan--</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>


                </select>
            </div>

        </div>



        <div class="col-md-3">
            <div class="form-group">

                <br><label style="font-weight: bold;">Tahun</label> <br>
                <select required="" class="form-control" name="tahun">

                    <option value="">--Pilih Tahun--</option>

                    <?php

                    $tahun = date("Y");

                    for ($i = $tahun - 3; $i <= $tahun; $i++) {
                        echo '

                        			<option value="' . $i . '">' . $i . '</option>

                        		';
                    }

                    ?>

                </select>
            </div>

        </div>




        <div class="col-md-1">
            <div class="form-group">
                <button type="submit" name="filter" style="margin-top: 45px;" class="btn btn-primary"><i class="fa fa-plus"></i> Buat Form</button>
            </div>

        </div>

</form>
<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                Tambah Data Tagihan
            </div>

            <form method="POST">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                    </div>

                    <thead>
                        <tr>
                            <th>No</th>

                            <th>Nama Pelanggan</th>
                            <th>Nama Paket</th>
                            <th>Harga</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $baris_per_halaman = 300; // Tentukan jumlah baris per halaman
                        $halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
                        $offset = ($halaman - 1) * $baris_per_halaman;

                        if (isset($_POST['filter'])) {
                            $bulan = $_POST['bulan'];
                            $tahun = $_POST['tahun'];
                            $bulantahun = $bulan . $tahun;
                        } else {
                            $bulan = date('m');
                            $tahun = date('Y');
                            $bulantahun = $bulan . $tahun;
                        }

                        ?>

                        <div class="callout callout-warning">
                            <p style="margin-left: 10px; font-size: 20px;">Bulan <?php echo $bulan ?>, Tahun <?php echo $tahun ?> </p>
                        </div>

                        <?php

                        if ($bulantahun != "") {

                            $no = 1;

                            $getData = $koneksi->query("SELECT * FROM tbl_paketmikrotik");
                            $data3 = $getData->fetch_assoc();

                            $sql = $koneksi->query("SELECT tb_pelanggan.*, tb_paket.nama_paket, tb_paket.harga, tb_paket.ppn
                            FROM tb_pelanggan
                            INNER JOIN tb_paket ON tb_pelanggan.paket = tb_paket.id_paket
                            WHERE NOT EXISTS(SELECT * FROM tb_tagihan WHERE bulan_tahun='$bulantahun' AND tb_pelanggan.id_pelanggan=tb_tagihan.id_pelanggan)
                            LIMIT $baris_per_halaman OFFSET $offset");

                            $jml_pegawai = $sql->num_rows;

                            while ($data = $sql->fetch_assoc()) {
                                $id_pelanggan = $data['id_pelanggan'];

                        ?>

                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <input type="hidden" class="form-control" name="id_pelanggan[]" value="<?php echo $id_pelanggan ?>" readonly>
                                    <input type="hidden" class="form-control" name="bulan2[]" value="<?php echo $bulantahun; ?>" readonly>
                                    </td>
                                    <td><?php echo $data['nama_pelanggan'] ?></td>
                                    <td><?php echo $data['nama_paket'] ?></td>

                                    <?php
                                    if ($data3['ppn'] == 'aktif') {
                                        $ppn = $data['harga'] * $data['ppn'];
                                        $total_harga = $data['harga'] + $ppn;
                                    } else {
                                        $total_harga = $data['harga'];
                                    }
                                    ?>

                                    <td><input style="width: 100px" type="text" class="form-control uang" name="harga[]" readonly="" value="<?php echo $total_harga ?>"></td>
                                </tr>

                        <?php
                            }
                        }
                        ?>
                        <?php if ($jml_pegawai > 0) { ?>
                            <button type="submit" name="simpan" class="btn btn-success">Simpan Tagihan</button>
                        <?php }  ?>
                        <small style="color: red;"> Maksimal 300 Data Jika Lebih Silakan Simpan Tagihan Kembali</small>
                    </tbody>

                    </table>
                </div>
            </form>
        </div>

    </div>

    <?php

    if (isset($_POST['simpan'])) {

        $id_pelanggan = $_POST['id_pelanggan'];
        $bulan2 = $_POST['bulan2'];
        $harga = $_POST['harga'];

        $harga_oke = str_replace(".", "", $harga);

        $jumlah = count($_POST['harga']);

        for ($i = 0; $i < $jumlah; $i++) {
            // Check if the record already exists
            $check_query = $koneksi->query("SELECT * FROM tb_tagihan WHERE id_pelanggan = '$id_pelanggan[$i]' AND bulan_tahun = '$bulan2[$i]' AND jml_bayar = '$harga_oke[$i]'");
            $existing_record = $check_query->fetch_assoc();

            if (!$existing_record) {
                // Record does not exist, perform the INSERT
                $sql = $koneksi->query("INSERT INTO tb_tagihan (id_pelanggan, bulan_tahun, jml_bayar, terbayar) VALUES ('$id_pelanggan[$i]', '$bulan2[$i]', '$harga_oke[$i]', null)");
                if ($sql) {
                    echo "
                    <script>
                        setTimeout(function() {
                            swal({
                                title: 'Data Tagihan',
                                text: 'Berhasil Disimpan!',
                                type: 'success'
                            }, function() {
                                window.location ='?page=transaksi';
                            });
                        }, 300);
                    </script>
                ";
                }
            } else {
                // Record already exists, you may want to handle this case (skip or show a message)
                echo "
                    <script>
                        setTimeout(function() {
                            swal({
                                title: 'Data Tagihan Sudah Tersimpan',
                                text: 'Silakan Tambah Data Lain Jika ada',
                                type: 'success'
                            }, function() {
                                window.location ='?page=transaksi';
                            });
                        }, 300);
                    </script>
                ";
            }
        }
    }


    ?>