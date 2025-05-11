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

        $getlog = $API->comm("/log/print", array("?topics" => "hotspot,info,debug"));
        $log = array_reverse($getlog);
        $recentLogs = array_slice($log, 0, 10);

        $activeHotspots = $API->comm("/ip/hotspot/active/print");
        $jumlahHotspotAktif = count($activeHotspots);

        $userAll = $API->comm("/ip/hotspot/user/print");
        $jumlahUserHotspot = count($userAll);

    ?>

        <div class="row">

            <div class="col-md-8">
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-12">
                                Hotspot View
                            </div>
                        </div>
                    </div>

                    <div class="panel-body ">

                        <div class="row">

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <a href="?page=monitoring-hotspot&aksi=details-active">
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <h3><?= $jumlahHotspotAktif ?></h3>
                                            <p>Hotspot Aktif</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <a href="?page=user_profile">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3><?= $jumlahUserHotspot ?></h3>
                                            <p>Stok Voucher</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-yellow">
                                    <div class="inner">
                                        <h3><?= ($kas_hari_ini !== NULL) ? number_format($kas_hari_ini, 0, ",", ".") : '0'; ?></h3>
                                        <p>Pemasukan Hari Ini</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3><?= ($kas_hari_ini !== NULL) ? number_format($kas_hari_ini, 0, ",", ".") : '0'; ?></h3>
                                        <p>Pemasukan Hari Ini</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-12">
                                Riwayat Pembelian Voucher
                            </div>
                        </div>
                    </div>

                    <div class="panel-body ">

                        <div class="row">



                        </div>

                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-12">
                                Log Hotspot
                            </div>
                        </div>
                    </div>

                    <div class="panel-body ">

                        <table class="table table-sm table-bordered table-hover">
                            <tr>
                                <td>Waktu</td>
                                <td>Pesan</td>
                            </tr>
                            <?php foreach ($recentLogs as $rc) { ?>
                                <tr>
                                    <td><?= $rc['time'] ?></td>
                                    <td><?= $rc['message'] ?></td>
                                </tr>
                            <?php } ?>
                        </table>

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