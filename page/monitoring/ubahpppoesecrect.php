<?php
session_start();
if (isset($_SESSION['admin']) || $_SESSION['teknisi'] == true) {
    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) {

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

        $getName = $_GET['nama'];

        // Retrieve the Simple Queues
        $API->write('/ppp/secret/print');
        $pppSecrets = $API->read();

        // Find the queue based on "name" (assuming "name" is unique)
        $selectedQueue = null;
        foreach ($pppSecrets as $qw) {
            if ($qw['name'] == $getName) {
                $selectedQueue = $qw;
                break;
            }
        }

        if ($selectedQueue) {
?>
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ubah PPPOE Secret</h3>
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="box-body">
                                <input type="hidden" value="<?= $selectedQueue['.id'] ?>" name="idPppoe">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">NAMA</label>
                                    <input type="text" name="name" class="form-control" value="<?= $selectedQueue['name'] ?>" placeholder="Wajib Di Isi" required>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">PASSWORD</label>
                                    <input type="text" class="form-control" name="password" value="<?= $selectedQueue['password'] ?>" placeholder="Kosongkan Jika Tidak Di Isi">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">SERVICE</label>
                                    <select class="form-control" id="exampleFormControlSelect1" name="service">
                                        <?php foreach ($serviceOptions as $value => $label) : ?>
                                            <?php if ($selectedQueue['service'] == $value) { ?>
                                                <option value="<?= $value ?>" selected><?= $label ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $value ?>"><?= $label ?></option>
                                            <?php } ?>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">CALLER ID</label>
                                    <input type="text" class="form-control" name="caller-id" value="<?= $selectedQueue['caller-id'] ?>" placeholder="Kosongkan Jika Tidak Di isi">
                                </div>

                                <?php
                                $API->write('/ppp/profile/print');
                                $t = $API->read();
                                ?>

                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">PROFILE</label>
                                    <select class="form-control" id="exampleFormControlSelect1" name="profile">
                                        <?php foreach ($t as $qw) { ?>
                                            <?php if ($qw['name'] == $selectedQueue['profile']) { ?>
                                                <option value="<?= $qw['name'] ?>" selected><?= $qw['name'] ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $qw['name'] ?>"><?= $qw['name'] ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">LOCAL ADDRESS</label>
                                    <input type="text" class="form-control" name="local-address" value="<?= $selectedQueue['local-address'] ?>" placeholder="Kosongkan Jika Tidak Di Isi">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">REMOTE ADDRESS</label>
                                    <input type="text" class="form-control" name="remote-address" value="<?= $selectedQueue['remote-address'] ?>" placeholder="Kosongkan Jika Tidak Di Isi">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">ROUTES</label>
                                    <input type="text" class="form-control" name="routes" value="<?= $selectedQueue['routes'] ?>" placeholder="Kosongkan Jika Tidak Di Isi">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">LIMIT BYTES IN</label>
                                    <input type="text" class="form-control" name="limit-bytes-in" value="<?= $selectedQueue['limit-bytes-in'] ?>" placeholder="Kosongkan Jika Tidak Di Isi">
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">LIMIT BYTES OUT</label>
                                    <input type="text" class="form-control" name="limit-bytes-out" value="<?= $selectedQueue['limit-bytes-out'] ?>" placeholder="Kosongkan Jika Tidak Di Isi">
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="goBack()">Kembali</button>
                                    <button type="submit" name="ubahpppoe" class="btn btn-primary">ubah</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }

        if (isset($_POST['ubahpppoe'])) {

            $id = $_POST['idPppoe'];
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
                ".id" => $id,
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

            $berhasilUpdate = $API->comm('/ppp/secret/set', $requestData);

            if (count($berhasilUpdate) == 0) {
            ?>

                <script>
                    setTimeout(function() {
                        swal({
                            title: 'PPPOE Secrets',
                            text: 'Data Berhasil Di Update',
                            type: 'success'
                        }, function() {
                            window.location = '?page=pppoe';
                        });
                    }, 300);
                </script>

<?php
            } else {
                echo "Gagal Mengubah Simple Queue. Pesan kesalahan: " . $API->error;
            }
        }
        $API->disconnect();
    }
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>