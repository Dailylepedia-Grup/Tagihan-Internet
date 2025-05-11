<?php
session_start();
if (isset($_SESSION['admin']) || $_SESSION['teknisi'] == true) {
?>

    <?php
    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) { ?>

        <?php
        $serviceOptions = array(
            'any' => 'any',
            'async' => 'async',
            'l2tp' => 'l2tp',
            'ovpn' => 'ovpn',
            'pppoe' => 'pppoe',
            'pptp' => 'pptp',
            'sstp' => 'sstp'
        );
        $selectedService = isset($_POST['service']) ? $_POST['service'] : '';
        ?>

        <div class="row">

            <div class="col-md-7">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tambah PPPOE Secrect</h3>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="exampleFormControlInput1">NAMA</label>
                                <input type="text" name="name" class="form-control" placeholder="Wajib Di Isi" required>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">PASSWORD</label>
                                <input type="text" class="form-control" name="password" placeholder="Kosongkan Jika Tidak Di Isi">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlSelect1">SERVICE</label>
                                <select class="form-control" id="exampleFormControlSelect1" name="service">
                                    <?php foreach ($serviceOptions as $value => $label) : ?>
                                        <option value="<?= $value ?>"><?= $label ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">CALLER ID</label>
                                <input type="text" class="form-control" name="caller-id" placeholder="Kosongkan Jika Tidak Di isi">
                            </div>

                            <?php
                            $API->write('/ppp/profile/print');
                            $t = $API->read();
                            ?>

                            <div class="form-group">
                                <label for="exampleFormControlSelect1">PROFILE</label>
                                <select class="form-control" id="exampleFormControlSelect1" name="profile">
                                    <?php foreach ($t as $s) : ?>
                                        <option value="<?= $s['name'] ?>"><?= $s['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">LOCAL ADDRESS</label>
                                <input type="text" class="form-control" name="local-address" placeholder="Kosongkan Jika Tidak Di Isi">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">REMOTE ADDRESS</label>
                                <input type="text" class="form-control" name="remote-address" placeholder="Kosongkan Jika Tidak Di Isi">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">ROUTES</label>
                                <input type="text" class="form-control" name="routes" placeholder="Kosongkan Jika Tidak Di Isi">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">LIMIT BYTES IN</label>
                                <input type="text" class="form-control" name="limit-bytes-in" placeholder="Kosongkan Jika Tidak Di Isi">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">LIMIT BYTES OUT</label>
                                <input type="text" class="form-control" name="limit-bytes-out" placeholder="Kosongkan Jika Tidak Di Isi">
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="goBack()">Kembali</button>
                                <button type="submit" name="tambahpppoe" class="btn btn-primary">Tambah PPP Secrect</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>

        <?php
        if (isset($_POST['tambahpppoe'])) {

            $name = $_POST['name'];
            $password = $_POST['password'];
            $service = $_POST['service'];
            $callerId = $_POST['caller-id'];
            $profile = $_POST['profile'];
            $localAddress = $_POST['local-address'];
            $remoteAddress = $_POST['remote-address'];
            $routes = $_POST['routes'];
            $limitIn = $_POST['limit-bytes-in'];
            $limitOut = $_POST['limit-bytes-out'];

            // periksa apakah password kosong atau tidak 
            if (empty($password)) {
                $password = "";
            }

            if (empty($callerId)) {
                $callerId = "";
            }

            if (empty($routes)) {
                $routes = "";
            }

            if (empty($limitIn)) {
                $limitIn = "";
            }
            if (empty($limitOut)) {
                $limitOut = "";
            }

            $requestData = array(
                "name" => $name,
                "password" => $password,
                "service" => $service,
                "profile" => $profile,
                "caller-id" => $callerId,
            );

            if (!empty($localAddress)) {
                $requestData["local-address"] = $localAddress;
            }

            if (!empty($remoteAddress)) {
                $requestData["remote-address"] = $remoteAddress;
            }

            $berhasil = $API->comm('/ppp/secret/add', $requestData);

            if ($berhasil) {
                echo "
        
                          <script>
                              setTimeout(function() {
                                  swal({
                                      title: 'PPPOE SECRET',
                                      text: 'Berhasil Di Tambahkan',
                                      type: 'success'
                                  }, function() {
                                      window.location = '?page=pppoe';
                                  });
                              }, 300);
                          </script>
        
                      ";
            }
        }
        ?>

    <?php
        $API->disconnect();
    } ?>

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>