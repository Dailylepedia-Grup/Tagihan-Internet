<?php
$result = $koneksi->query("SELECT COUNT(*) as total_location FROM tbl_odp WHERE location IS NOT NULL AND location <> ''");
$row = $result->fetch_assoc();
$totalLocations = $row['total_location'];
?>

<div class="row">

    <div class="col-md-12">
        <!-- Advanced Tables -->
        <a href="?page=odp&aksi=tambahodp" type="button" class="btn btn-success" style="margin-bottom: 10px;">
            Tambah ODP
        </a>
        <a href="?page=odp&aksi=listodp" type="button" class="btn btn-warning" style="margin-bottom: 10px;">
            Detail ODP
        </a>
        <div class="box box-primary box-solid">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-6">
                        Lokasi ODP Jumlah <?= $totalLocations ?> Titik
                    </div>
                </div>
            </div>

            <div class="panel-body mt-1">
                <div id="titikodp" style="height: 450px;"></div>
                <p id="odp"></p>
            </div>
        </div>
    </div>
</div>