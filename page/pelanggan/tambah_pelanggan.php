<?php

$conPelanggan = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
$checkUser = $conPelanggan->fetch_assoc();

if ($checkUser['status'] == 'ya') {
    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();
    if ($API->connect($row['ip'], $row['username'], $row['password'])) {
        $API->write('/ppp/secret/print');
        $pppSecret = $API->read();

        $API->write('/ppp/profile/print');
        $pppProfile = $API->read();
    }
}
?>

<div class="row">

    <div class="col-md-6">

        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Tambah Data Pelanggan</h3>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <div class="box-body">

                    <?php
                    $cek = $koneksi->query("SELECT * FROM tb_user WHERE level = 'user'");
                    $existingNames = array();

                    // Mengumpulkan nama yang sudah ada di dalam database ke dalam array
                    while ($row = $cek->fetch_assoc()) {
                        $existingNames[] = $row['username'];
                    }
                    ?>

                    <?php if ($checkUser['status'] == 'ya') { ?>
                        <div class="form-group">
                            <label>Ambil Data Dari Mikrotik</label>
                            <select class="form-control" id="getPMikrotik">
                                <option value="" data-nama="">Pilih Pelanggan</option>
                                <?php foreach ($pppSecret as $ps) {
                                    $name = $ps['name'];

                                    // Periksa apakah name sudah ada di dalam database
                                    $isNameExist = in_array($name, $existingNames);

                                    // Hanya tampilkan opsi jika name belum ada di dalam database
                                    if (!$isNameExist) {
                                ?>
                                        <option data-nama="<?= $ps['name'] ?>" data-profile="<?= $ps['profile'] ?>" data-username="<?= $ps['name'] ?>" data-password="<?= $ps['password'] ?>"><?= $ps['name'] ?></option>
                                <?php
                                    }
                                } ?>
                            </select>
                        </div>
                    <?php } ?>


                    <?php if ($checkUser['ippelanggan'] == 'statik') { ?>
                        <div class="form-group">
                            <label>LOCAL ADDRESS</label>
                            <input type="text" name="ip_address" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>REMOTE ADDRESS</label>
                            <input type="text" name="ip_address" class="form-control">
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <label>NIK KTP <small style="color: red;">*Kosongkan Jika Tidak Ada</small></label>
                        <input type="text" name="nik" id="nik" class="form-control">
                    </div>

                    <div class="form-group">
                        <?php if ($checkUser['ippelanggan'] == 'dynamic' || $checkUser['addppsecret'] == 'ya') { ?>
                            <label>Username (Pastikan Username Sama Dengan Nama PPP Secret Di Mikrotik)</label>
                        <?php } else { ?>
                            <label>Username</label>
                        <?php } ?>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <?php if ($checkUser['ippelanggan'] == 'dynamic' || $checkUser['addppsecret'] == 'ya') { ?>
                            <label>Password (Pastikan Password Sama Dengan Nama PPP Secret Di Mikrotik)</label>
                        <?php } else { ?>
                            <label>Password</label>
                        <?php } ?>
                        <input type="text" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="nama" id="nama_pelanggan" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Alamat </label>
                        <textarea class="form-control" rows="3" name="alamat"></textarea>
                    </div>

                    <div class="form-group">
                        <label>No Telp (Contoh 089612345678)</label>
                        <input type="text" name="no_telp" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="mapping" class="form-control" id="coordinates">
                    </div>

                    <div class="form-group">

                        <label>Paket :</label> <br>
                        <select class="form-control" name="paket" id="profile">

                            <?php

                            $query = $koneksi->query("SELECT * FROM tb_paket ORDER by id_paket");

                            while ($tampil_t = $query->fetch_assoc()) {
                                echo "<option value='$tampil_t[id_paket]'> $tampil_t[nama_paket]</option>";
                            }

                            ?>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Perangkat Modem :</label> <br>
                        <select class="form-control" name="nama_perangkat" id="perangkat">
                            <option value="NULL">== Jangan Sebutkan ==</option>
                            <?php

                            $queryy = $koneksi->query("SELECT * FROM tb_perangkat ORDER BY id_perangkat");

                            while ($tampil_tt = $queryy->fetch_assoc()) {
                                echo "<option value='$tampil_tt[id_perangkat]'> $tampil_tt[nama_perangkat]</option>";
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
                                echo "<option value='$tampil_tt[id_odp]'> $tampil_tt[nama_odp]</option>";
                            }

                            ?>

                        </select>

                    </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" name="tambah" class="btn btn-block btn-primary btn-lg">Simpan</button>

                </div>
            </form>

        </div>

    </div>

    <?php if ($checkUser['mapping'] == 'aktif') { ?>
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
    <?php } ?>

</div>

<?php
if (isset($_POST['tambah'])) {

    $username = htmlspecialchars(strip_tags($_POST['username']));
    $password = htmlspecialchars(strip_tags($_POST['password']));
    $nik = htmlspecialchars(strip_tags($_POST['nik']));
    $nama = htmlspecialchars(strip_tags($_POST['nama']));
    $tgl_pemasangan = (new DateTime())->format('Y-m-d H:i');
    $alamat = htmlspecialchars(strip_tags($_POST['alamat']));
    $no_telp = htmlspecialchars(strip_tags($_POST['no_telp']));
    $paket = $_POST['paket'];
    $odp = $_POST['odp'];
    $perangkat = ($_POST['nama_perangkat'] !== 'NULL') ? $_POST['nama_perangkat'] : NULL;

    $ip_address = $_POST['ip_address'];

    $mapping = $_POST['mapping'] ?: NULL;

    $tgl_pemasangan_obj = new DateTime($tgl_pemasangan);
    $tgl_pemasangan_obj->modify('+1 Month');

    $jam_sekarang = date('H:i:00'); // Mendapatkan jam saat ini

    $tgl_jatuh_tempo = $tgl_pemasangan_obj->format('Y-m-d') . ' ' . $jam_sekarang; // Menggabungkan tanggal dengan jam saat ini

    $sql3 = $koneksi->query("SELECT * FROM tb_user WHERE username='$username'");

    $sql3q = $koneksi->query("SELECT * FROM tb_pelanggan WHERE no_telp = '$no_telp'");
    $e = $sql3q->fetch_assoc();

    if ($e) { ?>
        <script type="text/javascript">
            alert("Nomor telepon sudah tercatat di database. Silahkan ulangi kembali.");
        </script>
    <?php
        // exit;
    }

    $getPaket = $koneksi->query("SELECT * FROM tb_paket WHERE id_paket=$paket");
    $checkPaket = $getPaket->fetch_assoc();
    $id_profile = $checkPaket['id_pmikrotik'];

    if ($paket == $checkPaket['id_paket']) {
        $namaPaket = $checkPaket['nama_paket'];
    }

    // Ambil nomor urut terakhir dari tabel tb_pelanggan
    $sql_last_id = $koneksi->query("SELECT MAX(id_pelanggan) AS last_id FROM tb_pelanggan");
    $row_last_id = $sql_last_id->fetch_assoc();
    $last_id = $row_last_id['last_id'];

    // Tambahkan 1 ke nomor urut terakhir
    $new_id = $last_id + 1;

    $kode_pelanggan = "WNG03100" . $new_id;

    $data = $sql3->num_rows;

    if ($data >= 1) {
    ?>
        <script type="text/javascript">
            alert("username Atatu Nomor Telp Ini Sudah Tercatat Di Database silahkan ulangi kembali");
        </script>
<?php
    } else {

        // Mengambil nilai port maksimum dari tabel odp
        $cek = $koneksi->query("SELECT port_odp FROM tbl_odp WHERE id_odp = $odp");
        $cekk = $cek->fetch_assoc();
        $maxPort = $cekk['port_odp'];

        // Menghitung total odp pada odc tertentu
        $sql1 = $koneksi->query("SELECT COUNT(*) AS total_odp FROM tb_pelanggan WHERE odp = $odp");
        $count = $sql1->fetch_assoc();
        $total_odp = $count['total_odp'];

        if ($total_odp < $maxPort) {
            // $sql = $koneksi->query("insert into tb_pelanggan (nik, nama_pelanggan, alamat, no_telp, paket, ip_address, tgl_pemasangan, jatuh_tempo, location, id_perangkat, odp)values('$nik', '$nama', '$alamat', '$no_telp', '$paket', '$ip_address', '$tgl_pemasangan', '$tgl_jatuh_tempo', '$mapping', '$perangkat', '$odp') ");
            $sql = $koneksi->query("INSERT INTO tb_pelanggan (nik, nama_pelanggan, alamat, no_telp, paket, ip_address, tgl_pemasangan, jatuh_tempo, location, id_perangkat, odp, kode_pelanggan) VALUES ('$nik', '$nama', '$alamat', '$no_telp', '$paket', '$ip_address', '$tgl_pemasangan', '$tgl_jatuh_tempo', '$mapping', '$perangkat', '$odp', '$kode_pelanggan') ");

            $id_pelanggan = $koneksi->insert_id;

            $sql = $koneksi->query("insert into tb_user (username, nama_user, password, level, foto, id_pelanggan)values('$username', '$nama', '$password', 'user', 'admin.png', '$id_pelanggan') ");

            if ($checkUser['addppsecret'] == 'ya') {

                $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
                $result = mysqli_query($koneksi, $sql_mikrotik);
                $row = mysqli_fetch_assoc($result);

                $API = new RouterosAPI();
                if ($API->connect($row['ip'], $row['username'], $row['password'])) {
                    $sql = $API->comm("/ppp/secret/add", array(
                        "name" => $username,
                        "password" => $password,
                        "service" => 'pppoe',
                        "profile" => $id_profile,
                    ));
                }
            }

            $checkNotif = $koneksi->query("SELECT * FROM tbl_npemasangan");
            $notifikasi = $checkNotif->fetch_assoc();

            $pesan = $notifikasi['pesan_notif'];
            $pesan = str_replace('$nama', $nama, $pesan);
            $pesan = str_replace('$alamat', $alamat, $pesan);
            $pesan = str_replace('$no_telp', $no_telp, $pesan);
            $pesan = str_replace('$paket', $namaPaket, $pesan);
            $pesan = str_replace('$tgl_pemasangan', $tgl_pemasangan, $pesan);
            $pesan = str_replace('$username', $username, $pesan);
            $pesan = str_replace('$password', $password, $pesan);

            if ($notifikasi['status_notif'] == 'aktif') {

                $sql_token = "SELECT * FROM tbl_token WHERE id_token = 1"; // Sesuaikan dengan query yang sesuai
                $result = mysqli_query($koneksi, $sql_token);
                $row = mysqli_fetch_assoc($result);
                $authorizationToken = $row['token'];

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.fonnte.com/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                        'target' => $no_telp,
                        'message' => $pesan,
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: ' . $authorizationToken
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                // echo $response;
            }

            if ($sql) {
                echo "

   <script>
       setTimeout(function() {
           swal({
               title: 'Data Pelanggan',
               text: 'Berhasil Disimpan!',
               type: 'success'
           }, function() {
               window.location = '?page=pelanggan';
           });
       }, 300);
   </script>

";
            }
        } else {
            echo "
            <script>
                setTimeout(function() {
                    swal({
                        title: 'Gagal',
                        text: 'Melebihi Batas Port ODC',
                        type: 'error'
                    }, function() {
                        window.location = '?page=pelanggan';
                    });
                }, 300);
            </script>
        ";
        }
    }
}

?>