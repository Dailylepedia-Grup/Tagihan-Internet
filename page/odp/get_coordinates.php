<?php
include("../../include/koneksi.php");

$result = $koneksi->query("SELECT * FROM tbl_odp");

$coordinates = array();

// Mengambil hasil query dan menyimpannya dalam array
while ($row = $result->fetch_assoc()) {
    if (!empty($row['location'])) {
        $coord_parts = explode(',', $row['location']);
        $lat = floatval(trim($coord_parts[0]));
        $lng = floatval(trim($coord_parts[1]));

        $coordinates[] = array(
            'nama_odp' => $row['nama_odp'],
            'port_odp' => $row['port_odp'],
            'lat' => $lat,
            'lng' => $lng
        );
    }
}

// Menutup koneksi
$koneksi->close();

// Mengirimkan koordinat dalam format JSON
header('Content-Type: application/json');
echo json_encode($coordinates);
