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

        $name = $_GET['name'];

        $API->write('/ppp/profile/print');
        $pppProfile = $API->read();

        $selectedQueue = null;
        foreach ($pppProfile as $qw) {
            if ($qw['name'] == $name) {
                $selectedQueue = $qw;
                break;
            }
        }

?>
        <?php if ($selectedQueue) { ?>

            <div class="row">

                <div class="col-md-6">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ubah PPPOE Profile</h3>
                        </div>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="box-body">

                                <input type="hidden" name="id_profile" value="<?= $selectedQueue['.id'] ?>">

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?= $selectedQueue['name'] ?>" placeholder="Wajib Di Isi" required>
                                </div>

                                <div class="form-group">
                                    <label for="localaddress">Local Address <small style="color: red;">* Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                    <input type="text" name="local-address" value="<?= $selectedQueue['local-address'] ?>" class="form-control">
                                </div>

                                <?php if ($data['ip_pool'] == 'tidak') { ?>

                                    <div class="form-group">
                                        <label for="remoteaddress">Remote Address <small style="color: red;">*Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                        <input type="text" name="remote-address" value="<?= $selectedQueue['remote-address'] ?>" class="form-control">
                                    </div>

                                <?php } else { ?>

                                    <div class="form-group">
                                        <label>Remote Address <small style="color: red;">*Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                        <select name="remote-address" class="form-control">
                                            <option value="">Tidak Ada</option>
                                            <?php
                                            $API->write('/ip/pool/print');
                                            $simpleQueues = $API->read();
                                            foreach ($simpleQueues as $sq) {
                                                if ($selectedQueue['remote-address'] == $sq['name']) { ?>
                                                    <option value="<?= $sq['name'] ?>" selected><?= $sq['name'] ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $sq['name'] ?>"><?= $sq['name'] ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <?php } ?>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Rate Limit <small style="color: red;">*Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                    <input type="text" name="rate-limit" class="form-control" value="<?= $selectedQueue['rate-limit'] ?>" placeholder="2M/2M">
                                </div>

                                <div class="form-group">
                                    <label>Parent Queue <small style="color: red;">*Sudah Di Isi Tidak Dapat Dikosongkan Harap Sesuaikan Di Winbox</small></label>
                                    <select name="parent-queue" class="form-control">
                                        <option value="">Tidak Ada</option>
                                        <?php
                                        $API->write('/queue/simple/print');
                                        $simpleQueues = $API->read();
                                        foreach ($simpleQueues as $sq) {
                                            if ($selectedQueue['parent-queue'] == $sq['name']) { ?>
                                                <option value="<?= $sq['name'] ?>" selected><?= $sq['name'] ?></option>
                                            <?php } else { ?>
                                                <option value="<?= $sq['name'] ?>"><?= $sq['name'] ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Script (On Up)</label>
                                    <textarea type="text" name="on-up" class="form-control"><?= $selectedQueue['on-up'] ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Script (On Down)</label>
                                    <textarea type="text" name="on-down" class="form-control"><?= $selectedQueue['on-up'] ?></textarea>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="goBack()">Kembali</button>
                                    <button type="submit" name="ubah" class="btn btn-primary">Ubah PPP Profile</button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>

            </div>

<?php
        }

        if (isset($_POST['ubah'])) {

            $id = $_POST['id_profile'];
            $name = $_POST['name'];
            $localAddress = $_POST['local-address'];
            $rateLimit = $_POST['rate-limit'];
            $remoteAddress = $_POST['remote-address'];
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
                ".id" => $id,
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

            $berhasil = $API->comm('/ppp/profile/set', $requestData);

            if (count($berhasil) == 0) {
                echo "
                    <script>
                        setTimeout(function() {
                            swal({
                                title: 'PPPOE Profile',
                                text: 'Berhasil Diubah',
                                type: 'success'
                            }, function() {
                                window.location = '?page=pppoeprofile';
                            });
                        }, 300);
                    </script>";
            } else {
                echo "Gagal mengubah profile: " . $API->error;
            }
        }
    }
}
?>