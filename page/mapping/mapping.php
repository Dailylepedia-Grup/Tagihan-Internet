<?php
$result = $koneksi->query("SELECT COUNT(*) as total_location FROM tb_pelanggan WHERE location IS NOT NULL AND location <> ''");
$row = $result->fetch_assoc();
$totalLocations = $row['total_location'];
?>

<div class="row">

    <div class="col-md-12">
        <!-- Advanced Tables -->

        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-6">
                        Lokasi Pelanggan Jumlah <?= $totalLocations ?> Titik
                    </div>
                </div>
            </div>

            <div class="panel-body mt-1">
                <div id="map" style="height: 450px;"></div>
                <p id="coordinates"></p>
            </div>
        </div>
    </div>
</div>