<?php
$conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
$checkUser = $conPelanggan->fetch_assoc();
?>

<div class="row">
    <!-- left column -->
    <div class="col-md-6">

        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                Ubah Data Pelanggan
            </div>
            <div class="modal-body">

                <form role="form" method="POST">

                    <?php

                    $id_pelanggan = $_GET['id'];

                    $sql1 = $koneksi->query("SELECT * FROM tb_pelanggan WHERE id_pelanggan='$id_pelanggan'");

                    while ($data1 = $sql1->fetch_assoc()) {

                        $formattedDate = date('Y-m-d H:i', strtotime($data1['jatuh_tempo']));

                    ?>

                        <input type="hidden" name="id_pelanggan" value="<?php echo $data1['id_pelanggan']; ?>">

                        <?php if ($checkUser['ippelanggan'] == 'statik') { ?>
                            <div class="form-group">
                                <label>IP Address</label>
                                <input type="text" name="ip_address" class="form-control" value="<?php echo $data1['ip_address']; ?>">
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <label>Jatuh Tempo</label>
                            <input type="text" name="jatuhtempo" class="form-control" value="<?php echo $formattedDate; ?>" required="">
                        </div>

                        <div class="form-group">
                            <label>NIK KTP <small style="color: red;">*Kosongkan Jika Tidak Ada</small></label>
                            <input type="text" name="nik" class="form-control" value="<?php echo $data1['nik']; ?>">
                        </div>

                        <div class="form-group">
                            <label>Nama Pelanggan</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo $data1['nama_pelanggan']; ?>" required="">
                        </div>

                        <div class="form-group">
                            <label>Alamat </label>
                            <textarea class="form-control" rows="3" name="alamat"><?php echo $data1['alamat']; ?></textarea>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="mapping" class="form-control" value="<?php echo $data1['location'] ?>" id="coordinates">
                        </div>

                        <div class="form-group">
                            <label>No Telp (Contoh 089612345678)</label>
                            <input type="text" name="no_telp" class="form-control" value="<?php echo $data1['no_telp']; ?>" required="">
                        </div>

                        <div class="form-group">

                            <label>Paket :</label> <br>
                            <select class="form-control" name="paket">

                                <?php

                                $query = $koneksi->query("SELECT * FROM tb_paket ORDER by id_paket");

                                while ($tampil_t = $query->fetch_assoc()) {
                                    $pilih_t = ($tampil_t['id_paket'] == $data1['paket'] ? "selected" : "");
                                    echo "<option value='$tampil_t[id_paket]' $pilih_t> $tampil_t[nama_paket]</option>";
                                }

                                ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Perangkat Modem :</label> <br>
                            <select class="form-control" name="perangkat">
                                <?php
                                $queryy = $koneksi->query("SELECT * FROM tb_perangkat ORDER BY id_perangkat");

                                $selectedNull = ($data1['id_perangkat'] === NULL || $data1['id_perangkat'] === '0') ? "selected" : "";
                                echo "<option value='NULL' $selectedNull>== Jangan Sebutkan ==</option>";

                                while ($tampil_tt = $queryy->fetch_assoc()) {
                                    $pilih_tt = ($tampil_tt['id_perangkat'] == $data1['id_perangkat']) ? "selected" : "";
                                    echo "<option value='$tampil_tt[id_perangkat]' $pilih_tt> $tampil_tt[nama_perangkat]</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">

                            <label>Dari ODP</label> <br>
                            <select class="form-control" name="odp">
                                <option value="NULL">== Tidak Ada ==</option>
                                <?php

                                $queryy = $koneksi->query("SELECT * FROM tbl_odp ORDER BY id_odp");

                                while ($tampil_tt = $queryy->fetch_assoc()) {
                                    $pilih = ($tampil_tt['id_odp'] == $data1['odp'] ? "selected" : "");
                                    echo "<option value='$tampil_tt[id_odp]' $pilih > $tampil_tt[nama_odp]</option>";
                                }

                                ?>

                            </select>

                        </div>

            </div>
            <div class="modal-footer">

                <button type="submit" name="simpan" class="btn btn-block btn-primary btn-lg">Simpan</button>

            </div>

        <?php } ?>

        </form>

        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Lokasi Pelanggan</h3>
            </div>

            <div class="box-body">
                <div id="map" style="height: 600px;"></div>
            </div>
        </div>
    </div>

</div>

<?php

if (isset($_POST['simpan'])) {
    $id_pelanggan_ubah = $_POST['id_pelanggan'];
    $nama = htmlspecialchars(strip_tags($_POST['nama']));
    $nik = htmlspecialchars(strip_tags($_POST['nik']));
    $jtempo = $_POST['jatuhtempo'];
    $alamat = htmlspecialchars(strip_tags($_POST['alamat']));
    $no_telp = htmlspecialchars(strip_tags($_POST['no_telp']));
    $paket = htmlspecialchars(strip_tags($_POST['paket']));
    $ip_address = $_POST['ip_address'];
    $odp = $_POST['odp'];
    $mapping = $_POST['mapping'] ?: NULL;
    $perangkat = $_POST['perangkat'] ?: NULL;

    $sql = $koneksi->query("UPDATE tb_pelanggan SET 
                nik='$nik',
                nama_pelanggan='$nama',
                alamat='$alamat', 
                no_telp='$no_telp',
                paket='$paket',
                ip_address ='$ip_address',
                jatuh_tempo = '$jtempo',
                location = '$mapping',
                odp = $odp,
                id_perangkat = '$perangkat'
                WHERE id_pelanggan='$id_pelanggan_ubah'");

    $getPaket = $koneksi->query("SELECT * FROM tb_paket WHERE id_paket=$paket");
    $checkPaket = $getPaket->fetch_assoc();
    $id_profile = $checkPaket['id_pmikrotik'];

    if ($checkUser['status'] == 'ya') {
        $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
        $result = mysqli_query($koneksi, $sql_mikrotik);
        $row = mysqli_fetch_assoc($result);

        $API = new RouterosAPI();
        if ($API->connect($row['ip'], $row['username'], $row['password'])) {
            // Mengambil ID PPP Secret berdasarkan nama pengguna                    
            $getPPPId = $API->comm("/ppp/secret/print", array(
                "?name" => $nama,
            ));

            if (!empty($getPPPId)) {
                $pppId = $getPPPId[0]['.id'];

                $API->comm("/ppp/secret/set", array(
                    ".id" => $pppId,
                    "profile" => $id_profile,
                ));
            } else {
                echo "PPP Secret dengan nama pengguna $nama tidak ditemukan.";
            }
        }
    }

    if ($sql) {
        echo "

<script>
    setTimeout(function() {
        swal({
            title: 'Data Pelanggan',
            text: 'Berhasil Diubah!',
            type: 'success'
        }, function() {
            window.location = '?page=pelanggan';
        });
    }, 300);
</script>

";
    }
}


?>