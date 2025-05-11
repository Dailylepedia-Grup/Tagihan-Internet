<?php
session_start();
if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {

    $getData = $koneksi->query("SELECT * FROM tbl_penggunamikrotik");
    $data = $getData->fetch_assoc();

    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) {

?>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tambah PPPOE Profile</h3>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Wajib Di Isi" required>
                            </div>

                            <div class="form-group">
                                <label>Local Address <small style="color: red;">*Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                <input type="text" name="local-address" class="form-control">
                            </div>

                            <?php if ($data['ip_pool'] == 'tidak') { ?>
                                <div class="form-group">
                                    <label>Remote Address <small style="color: red;">*Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                    <input type="text" name="remote-address" class="form-control">
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label>Remote Address <small style="color: red;">*Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                    <select name="remote-address" class="form-control">
                                        <option value="">Tidak Ada</option>
                                        <?php
                                        $API->write('/ip/pool/print');
                                        $simpleQueues = $API->read();
                                        foreach ($simpleQueues as $sq) { ?>
                                            <option value="<?= $sq['name'] ?>"><?= $sq['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <label>Rate Limit <small style="color: red;">*Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                <input type="text" name="rate-limit" class="form-control" placeholder="2M/2M">
                            </div>

                            <div class="form-group">
                                <label>Parent Queue <small style="color: red;">*Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                <select name="parent-queue" class="form-control">
                                    <option value="">Tidak Ada</option>
                                    <?php
                                    $API->write('/queue/simple/print');
                                    $simpleQueues = $API->read();
                                    foreach ($simpleQueues as $sq) { ?>
                                        <option value="<?= $sq['name'] ?>"><?= $sq['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Script (On Up)</label>
                                <textarea type="text" name="on-up" class="form-control"> </textarea>
                            </div>

                            <div class="form-group">
                                <label>Script (On Down)</label>
                                <textarea type="text" name="on-down" class="form-control"></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="goBack()">Kembali</button>
                                <button type="submit" name="kirim" class="btn btn-primary">Tambah PPP Profile</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php
        if (isset($_POST['kirim'])) {

            $name = $_POST['name'];
            $localAddress = $_POST['local-address'];
            $remoteAddress = $_POST['remote-address'];
            $rateLimit = $_POST['rate-limit'];
            $parentQueue = $_POST['parent-queue'];
            $onUp = $_POST['on-up'];
            $onDown = $_POST['on-down'];

            if (empty($onUp)) {
                $onUp = "";
            }

            if (empty($onDown)) {
                $onDown = "";
            }

            $requestData = array(
                "name" => $name,
                "on-up" => $onUp,
                "on-down" => $onDown
            );

            if (!empty($localAddress)) {
                $requestData["local-address"] = $localAddress;
            }

            if (!empty($remoteAddress)) {
                $requestData["remote-address"] = $remoteAddress;
            }

            if (!empty($rateLimit)) {
                $requestData["rate-limit"] = $rateLimit;
            }

            if (!empty($parentQueue)) {
                $requestData["parent-queue"] = $parentQueue;
            }

            $berhasil = $API->comm('/ppp/profile/add', $requestData);

            if ($berhasil) {
                echo "
                    <script>
                        setTimeout(function() {
                            swal({
                                title: 'PPPOE Profile',
                                text: 'Berhasil Ditambahkan',
                                type: 'success'
                            }, function() {
                                window.location = '?page=pppoeprofile';
                            });
                        }, 300);
                    </script>
                ";
            } else {
                echo "Gagal menambahkan profile: " . $API->error;
            }
        }
    }
    ?>

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>