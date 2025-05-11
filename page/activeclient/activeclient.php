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
            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php
                $API->write('/ppp/active/print');
                $activePPPoeClients = $API->read();
                $hitungSemua = count($activePPPoeClients);

                ?>
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"><b>PPPOE AKTIF</b></span>
                        <span class="info-box-text">TOTAL : <?= $hitungSemua; ?> PELANGGAN</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <?php $API->write('/ppp/secret/print');
                $allPPPoeClients = $API->read();

                $API->write('/ppp/active/print');
                $activePPPoeClients = $API->read();

                if (!empty($allPPPoeClients) && !empty($activePPPoeClients)) {
                    $numAllClients = count($allPPPoeClients);
                    $numActiveClients = count($activePPPoeClients);

                    $numInactiveClients = $numAllClients - $numActiveClients;

                    $totalNonAktif = $numInactiveClients . " Tidak Aktif";
                } else {
                    $totalNonAktif =  "Tidak ada pelanggan PPPoE";
                }
                ?>
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text"><b>PPPOE TIDAK AKTIF</b></span>
                        <span class="info-box-text">TOTAL : <?= $totalNonAktif ?></span>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="box box-primary box-solid">
                    <div class="box-header with-border">
                        PPPOE AKTIF & TIDAK AKTIF
                    </div>
                    <div class="panel-body">

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Last Logged Out</th>
                                        <th>Status / Internet</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $i = 1;
                                    $API->write('/ppp/secret/print');
                                    $pppSecret = $API->read();

                                    $API->write('/ppp/active/print');
                                    $activeClient = $API->read();

                                    // Mendapatkan IP address list
                                    $q = $koneksi->query("SELECT * FROM tb_pelanggan");
                                    while ($ambilUser = $q->fetch_assoc()) {
                                        $myIp = 'Blokir Bulanan ' . $ambilUser['ip_address'];
                                    }

                                    $asd = $API->comm("/ip/firewall/address-list/print", array(
                                        "?comment" => $myIp,
                                    ));

                                    $disabledClients = array(); // Menyimpan nama klien yang akan ditampilkan sebagai "Tidak Aktif"

                                    // Memeriksa apakah $asd memiliki entri dan mengambil alamat IP jika ada
                                    if (!empty($asd)) {
                                        $address = $asd[0]['address'];

                                        foreach ($activeClient as $ac) {
                                            // Mengecek apakah alamat IP ada di address list
                                            if ($ac['address'] == $address) {
                                                $disabledClients[] = $ac['name'];
                                            }
                                        }
                                    }

                                    foreach ($activeClient as $ac) {
                                        $found = false; // Menandakan apakah nama ditemukan atau tidak

                                        // Mengecek apakah nama ada di ppp secret
                                        foreach ($pppSecret as $secret) {
                                            if ($secret['name'] == $ac['name']) {
                                                $found = true;
                                                break;
                                            }
                                        }

                                        // Menentukan warna berdasarkan hasil pencarian
                                        $rowColor = $found ? 'green' : 'red';
                                    ?>
                                        <tr style="color: <?= $rowColor; ?>">
                                            <td><?= $i++; ?></td>
                                            <td><?= $ac['name']; ?></td>
                                            <td><?= $secret['last-logged-out'] ?></td>
                                            <!-- Tambahkan kolom untuk menunjukkan status aktif/tidak aktif -->
                                            <td><?= in_array($ac['name'], $disabledClients) ? 'Terisolir' : ($ac['disabled'] == 'true' ? 'Tidak Aktif' : 'Aktif'); ?></td>
                                        </tr>
                                        <?php
                                    }

                                    // Menampilkan entri dari ppp secret yang tidak aktif dan tidak terdapat di ppp active
                                    foreach ($pppSecret as $secret) {

                                        $found = false;

                                        // Mengecek apakah nama ada di ppp active
                                        foreach ($activeClient as $ac) {
                                            if ($secret['name'] == $ac['name']) {
                                                $found = true;
                                                break;
                                            }
                                        }

                                        // Jika nama tidak ditemukan di ppp active, tampilkan sebagai tidak aktif
                                        if (!$found && !in_array($secret['name'], $disabledClients)) {
                                        ?>
                                            <tr style="color: red;">
                                                <td><?= $i++; ?></td>
                                                <td><?= $secret['name']; ?></td>
                                                <td><?= $secret['last-logged-out'] ?></td>
                                                <td><?php echo ($secret['disabled'] == 'true') ? 'Isolir' : 'Tidak Aktif'; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        $API->disconnect();
    }
    ?>

<?php
} else {
    echo "Anda Tidak Berhak Mengakses Halaman Ini";
}
?>