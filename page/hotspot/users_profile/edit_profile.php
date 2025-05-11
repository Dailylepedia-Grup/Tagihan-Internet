<?php
session_start();
if (isset($_SESSION['admin']) || $_SESSION['teknisi'] == true) {
?>

    <?php
    $sql_mikrotik = "SELECT * FROM tbl_mikrotik WHERE id_mikrotik = 1";
    $result = mysqli_query($koneksi, $sql_mikrotik);
    $row = mysqli_fetch_assoc($result);

    $API = new RouterosAPI();

    if ($API->connect($row['ip'], $row['username'], $row['password'])) {

        $id = $_GET['id'];

        $getprofile = $API->comm("/ip/hotspot/user/profile/print");
        $srvlist = $API->comm("/ip/pool/print");
        $parent = $API->comm("/queue/simple/print");

        $selectProfile = null;
        foreach ($getprofile as $gp) {
            if ($gp['.id'] == $id) {
                $selectProfile = $gp;
                break;
            }
        }


        if (isset($_POST['edit'])) {

            $name = $_POST['name'];
            $addressPool = $_POST['address_pool'];
            $sharedUsers = $_POST['shared_users'];
            $rateLimit = $_POST['rate_limit'];
            $parentQueue = $_POST['parent_queue'];

            $requestData = array(
                "name" => $name,
            );

            if (!empty($addressPool)) {
                $requestData['address-pool'] = $addressPool;
            }

            if (!empty($sharedUsers)) {
                $requestData['shared-users'] = $sharedUsers;
            }

            if (!empty($rateLimit)) {
                $requestData['rate-limit'] = $rateLimit;
            }

            if (!empty($parentQueue)) {
                $requestData['parent-queue'] = $parentQueue;
            }

            $berhasil = $API->comm('/ip/hotspot/user/profile/set', $requestData);

            if ($berhasil) {
                echo "
        
                          <script>
                              setTimeout(function() {
                                  swal({
                                      title: 'Hotspot Profile',
                                      text: 'Berhasil Di Tambahkan',
                                      type: 'success'
                                  }, function() {
                                      window.location = '?page=users-profile';
                                  });
                              }, 300);
                          </script>
        
                      ";
            }
        }


    ?>

        <div class="row">

            <div class="col-md-8">
                <!-- Advanced Tables -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        Edit Profile Profile
                    </div>
                    <div class="panel-body">
                        <form method="POST">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Name<small style="color: red;">*</small></label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" value="<?= $selectProfile['name'] ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Address Pool<small style="color: red;">*</small></label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="address_pool">
                                        <option>none</option>
                                        <?php foreach ($srvlist as $q) : ?>
                                            <option value="<?= $q['name'] ?>"><?= $q['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Shared Users<small style="color: red;">*</small></label>
                                <div class="col-sm-10">
                                    <input type="text" name="shared_users" value="<?= $selectProfile['shared-users'] ?>" class="form-control" placeholder="1">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Rate Limit</label>
                                <div class="col-sm-10">
                                    <input type="text" name="rate_limit" value="<?= $selectProfile['rate-limit'] ?>" class="form-control" placeholder="2M/2M">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Parent Queue</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="parent_queue">
                                        <option>none</option>
                                        <?php foreach ($parent as $p) : ?>
                                            <option value="<?= $p['name'] ?>"><?= $p['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" name="edit" class="btn btn-primary">Edit Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Advanced Tables -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        Panduan
                    </div>
                    <div class="panel-body">

                        <li><b>Tanda Bintang Merah</b></li>
                        <li style="margin-left: 20px; list-style-type: none; text-align: justify;">
                            Form yang di tandai bintang merah wajib di isi, jika tidak akan menimbulkan error
                        </li>

                        <li><b>Format Tambah Profile</b></li>
                        <li style="margin-left: 20px; list-style-type: none; text-align: justify;">
                            Format input tetap seperti yang ada di winbox tidak ada yang berubah sama sekali
                        </li>

                    </div>
                </div>
            </div>


        </div>

    <?php }
    $API->disconnect();
    ?>

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>