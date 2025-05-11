<div class="row">

    <div class="col-md-6">

        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Tambah Data Paket</h3>
            </div>

            <form role="form" method="POST">
                <div class="box-body">

                    <?php
                    $cekStatus = $koneksi->query("SELECT * FROM tbl_paketmikrotik WHERE id_paketmikrotik = 1");
                    $ceks = $cekStatus->fetch_assoc();

                    if ($ceks['status'] == 'ya') {

                        $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
                        $result = mysqli_query($koneksi, $sql_mikrotik);
                        $row = mysqli_fetch_assoc($result);

                        $API = new RouterosAPI();
                        if ($API->connect($row['ip'], $row['username'], $row['password'])) {
                            $API->write('/ppp/profile/print');
                            $pppProfile = $API->read();
                    ?>
                            <div class="form-group">
                                <label for="paketPPP">Dari Profile PPPOE Profile</label>
                                <select class="form-control" name="profileppp" id="paketPPP">
                                    <?php foreach ($pppProfile as $paketProfile) : ?>
                                        <option value="<?= $paketProfile['.id'] ?>" data-nama="<?= $paketProfile['name'] ?>"><?= $paketProfile['name'] ?></option>
                                    <?php endforeach; ?>
                                    <option value="NULL">Tidak Ada di Dalam PPPOE Profile</option>
                                </select>
                            </div>
                    <?php
                        }
                    }

                    ?>

                    <div class=" form-group">
                        <?php
                        $getData = $koneksi->query("SELECT * FROM tbl_paketmikrotik");
                        $data = $getData->fetch_assoc();
                        ?>
                        <?php if ($data['status'] == 'ya') { ?>
                            <label>Paket (JANGAN MERUBAH NAMA KECUALI TIDAK ADA DALAM PPPPOE)</label>
                        <?php } else { ?>
                            <label>Paket</label>
                        <?php } ?>
                        <input required="" type="text" name="nama_paket" id="nama_paket" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Harga Paket</label>
                        <input required="" type="text" name="harga" class="form-control uang">
                    </div>

                    <?php if ($ceks['ppn'] == 'aktif') { ?>
                        <div class="form-group">
                            <label>PPN <small style="color: red;">(Contoh : 10% atau 5%)</small></label>
                            <input required="" type="text" name="tarif_ppn" class="form-control ">
                        </div>
                    <?php } ?>

                    <div class="modal-footer">
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </form>

        </div>

    </div>

</div>

<?php

if (isset($_POST['tambah'])) {
    $nama_paket = htmlspecialchars(strip_tags($_POST['nama_paket']));
    $harga = $_POST['harga'];
    $harga_oke2 = str_replace(".", "", $harga);
    $pmikrotik = $_POST['profileppp'];
    $pmikrotik = ($pmikrotik === "NULL") ? NULL : $pmikrotik;

    $queryPPN = $koneksi->query("SELECT * FROM tbl_paketmikrotik");
    $cekPPN = $queryPPN->fetch_assoc();

    if ($cekPPN && $cekPPN['ppn'] == "aktif") {
        $persentase_ppn = isset($_POST['tarif_ppn']) ? $_POST['tarif_ppn'] : NULL;
        $nilai_ppn = ($persentase_ppn !== NULL) ? $persentase_ppn / 100 : NULL;
    } else {
        $nilai_ppn = NULL;
    }

    // Gunakan parameterized queries untuk menghindari SQL injection
    $sql = $koneksi->prepare("INSERT INTO tb_paket (nama_paket, harga, ppn, id_pmikrotik) VALUES (?, ?, ?, ?)");

    // Periksa apakah prepare() berhasil
    if ($sql) {
        // Bind parameter dan jalankan query
        $sql->bind_param("ssss", $nama_paket, $harga_oke2, $nilai_ppn, $pmikrotik);
        $sql->execute();

        // Cek apakah query berhasil dieksekusi
        if ($sql->affected_rows > 0) {
            echo "<script>
                setTimeout(function() {
                    swal({
                        title: 'Data Paket',
                        text: 'Berhasil Disimpan!',
                        type: 'success'
                    }, function() {
                        window.location = '?page=paket';
                    });
                }, 300);
            </script>";
        } else {
            echo "Gagal menambahkan data. Error: " . $sql->error;
        }

        // Tutup statement
        $sql->close();
    } else {
        echo "Gagal menyiapkan query. Error: " . $koneksi->error;
    }
}


?>