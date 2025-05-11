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

        $getprofile = $API->comm("/ip/hotspot/user/profile/print");
        $srvlist = $API->comm("/ip/hotspot/print");

    ?>

        <div class="row">

            <div class="col-md-8">
                <!-- Advanced Tables -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        Add Users
                    </div>
                    <div class="panel-body">
                        <form>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Servers</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="exampleFormControlSelect1">
                                        <option>all</option>
                                        <?php foreach ($srvlist as $q) : ?>
                                            <option value="<?= $q['name'] ?>"><?= $q['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="text" name="password" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Profile</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="exampleFormControlSelect1" name="profile">
                                        <?php foreach ($getprofile as $g) : ?>
                                            <option value="<?= $g['name'] ?>"><?= $g['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Time Limit</label>
                                <div class="col-sm-10">
                                    <input type="text" name="time-limit" class="form-control" placeholder="00:00:00">
                                </div>

                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Data Limit</label>
                                <div class="col-sm-8">
                                    <input type="text" name="data-limit" class="form-control">
                                </div>
                                <div class="col-sm-2">
                                    <select class="form-control" id="exampleFormControlSelect1">
                                        <option value="">MB</option>
                                        <option value="">GB</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Comment</label>
                                <div class="col-sm-10">
                                    <input type="text" name="time-limit" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Sign in</button>
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

                        <li><b>Format Time Limit</b></li>
                        <li style="margin-left: 20px; list-style-type: none; text-align: justify;">
                            Seperti Format yang ada di mikrotik Contoh 03:00:00 Untuk 3 Jam atau 1d 00:00:00 Untuk 1 Hari
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