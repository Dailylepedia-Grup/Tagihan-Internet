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
        <div class="row">

            <div class="col-md-8">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tambah Simple Queue</h3>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Nama</label>
                                <input type="text" class="form-control" id="queueName" name="queueName" placeholder="Wajib Di Isi">
                            </div>

                            <div class="form-group">
                                <label for="exampleFormControlInput1">Target IP</label>
                                <input type="text" class="form-control" id="target" name="target" placeholder="192.168.88.1">
                            </div>

                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Upload Limit (0/512k/1M)</label>
                                    <input type="text" class="form-control" id="uploadLimit" name="uploadLimit" placeholder="Kosongkan Jika Unlimited">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Download Limit (0/512k/1M)</label>
                                    <input type="text" class="form-control" id="downloadLimit" name="downloadLimit" placeholder="Kosongkan Jika Unlimited">
                                </div>

                            </div>

                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Burst Limit Upload (0/512k/1M)</label>
                                    <input type="text" class="form-control" id="burst-limit-upload" name="burst-limit-upload" placeholder="Kosongkan Jika Unlimited">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Burst Limit Download (0/512k/1M)</label>
                                    <input type="text" class="form-control" id="burst-limit-download" name="burst-limit-download" placeholder="Kosongkan Jika Unlimited">
                                </div>

                            </div>

                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Burst Threshold Upload (0/512k/1M)</label>
                                    <input type="text" class="form-control" id="burst-threshold-upload" name="burst-threshold-upload" placeholder="Kosongkan Jika Unlimited">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Burst Threshold Download (0/512k/1M)</label>
                                    <input type="text" class="form-control" id="burst-threshold-download" name="burst-threshold-download" placeholder="Kosongkan Jika Unlimited">
                                </div>

                            </div>

                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Burst Time Upload (Kosongkan Jika Default)</label>
                                    <input type="text" class="form-control" id="burst-time-upload" name="burst-time-upload" placeholder="0s">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Burst Time Download (Kosongkan Jika Default)</label>
                                    <input type="text" class="form-control" id="burst-time-download" name="burst-time-download" placeholder="0s">
                                </div>

                            </div>

                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Limit At Upload (0/512k/1M)</label>
                                    <input type="text" class="form-control" id="limit-at-upload" name="limit-at-upload" placeholder="Kosongkan Jika Unlimited">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Limit At Download (0/512k/1M)</label>
                                    <input type="text" class="form-control" id="limit-at-download" name="limit-at-download" placeholder="Kosongkan Jika Unlimited">
                                </div>

                            </div>

                            <div class="form-group">
                                <?php
                                $API->write('/queue/simple/print');
                                $simpleQueues = $API->read();
                                ?>
                                <label for="exampleFormControlSelect1">Parent</label>
                                <select class="form-control" id="parentQueueName" name="parentQueueName">
                                    <option value="none">none</option> <!-- Tambahkan ini -->
                                    <?php foreach ($simpleQueues as $qw) {
                                    ?>
                                        <option value="<?= $qw['name'] ?>"><?= $qw['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="goBack()">Kembali</button>
                            <button type="submit" name="simplequeueAdd" class="btn btn-primary">Tambah Simple Queue</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

        <!-- Tambah Simple Quere Trigger -->
        <?php
        if (isset($_POST['simplequeueAdd'])) {

            $queueName = $_POST['queueName'];
            $target = $_POST['target'];

            $uploadLimit = isset($_POST['uploadLimit']) && $_POST['uploadLimit'] !== '' ? $_POST['uploadLimit'] : 0;
            $downloadLimit = isset($_POST['downloadLimit']) && $_POST['downloadLimit'] !== '' ? $_POST['downloadLimit'] : 0;

            $burstLimitUpload = isset($_POST['burst-limit-upload']) && $_POST['burst-limit-upload'] !== '' ? $_POST['burst-limit-upload'] : 0;
            $burstLimitDownload = isset($_POST['burst-limit-download']) && $_POST['burst-limit-download'] !== '' ? $_POST['burst-limit-download'] : 0;

            $burstThresholdUpload = isset($_POST['burst-threshold-upload']) && $_POST['burst-threshold-upload'] !== '' ? $_POST['burst-threshold-upload'] : 0;
            $burstThresholdDownload = isset($_POST['burst-threshold-download']) && $_POST['burst-threshold-download'] !== '' ? $_POST['burst-threshold-download'] : 0;

            $burstTimeUpload = isset($_POST['burst-time-upload']) && $_POST['burst-time-upload'] !== '' ? $_POST['burst-time-upload'] : 0;
            $burstTimeDownload = isset($_POST['burst-time-download']) && $_POST['burst-time-download'] !== '' ? $_POST['burst-time-download'] : 0;

            $limitAtUpload = isset($_POST['limit-at-upload']) && $_POST['limit-at-upload'] !== '' ? $_POST['limit-at-upload'] : 0;
            $limitAtDownload = isset($_POST['limit-at-download']) && $_POST['limit-at-download'] !== '' ? $_POST['limit-at-download'] : 0;

            $parentQueueName = $_POST['parentQueueName'];

            $berhasil = $API->comm("/queue/simple/add", array(
                "name" => $queueName,
                "target" => $target,
                "max-limit" => "$uploadLimit/$downloadLimit",
                "burst-limit" => "$burstLimitUpload/$burstLimitDownload",
                "burst-threshold" => "$burstThresholdUpload/$burstThresholdDownload",
                "burst-time" => "$burstTimeUpload/$burstTimeDownload",
                "limit-at" => "$limitAtUpload/$limitAtDownload",
                "parent" => $parentQueueName,
            ));

            if ($berhasil) {
                echo "
                  <script>
                      setTimeout(function() {
                          swal({
                              title: 'Simple Queues',
                              text: 'Berhasil Ditambahkan',
                              type: 'success'
                          }, function() {
                              window.location = '?page=simplequeue';
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