<?php
$result = $koneksi->query("SELECT COUNT(*) as total_location FROM tbl_odc WHERE location IS NOT NULL AND location <> ''");
$row = $result->fetch_assoc();
$totalLocations = $row['total_location'];
?>

<div class="row">

    <div class="col-md-12">
        <!-- Advanced Tables -->
        <a href="?page=odc&aksi=tambahodc" type="button" class="btn btn-success" style="margin-bottom: 10px;">
            Tambah ODC
        </a>
        <a href="?page=odc&aksi=listodc" type="button" class="btn btn-warning" style="margin-bottom: 10px;">
            Detail ODC
        </a>
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-6">
                        Lokasi ODC Jumlah <?= $totalLocations ?> Titik
                    </div>
                </div>
            </div>

            <div class="panel-body mt-1">
                <div id="titikodc" style="height: 450px;"></div>
                <p id="odc"></p>
            </div>
        </div>
    </div>
</div>