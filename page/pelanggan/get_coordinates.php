<?php
include("../../include/koneksi.php");

$result = $koneksi->query("SELECT * FROM tb_pelanggan");

$coordinates = array();

// Mengambil hasil query dan menyimpannya dalam array
while ($row = $result->fetch_assoc()) {
    if (!empty($row['location'])) {
        $coord_parts = explode(',', $row['location']);
        $lat = floatval(trim($coord_parts[0]));
        $lng = floatval(trim($coord_parts[1]));

        $coordinates[] = array(
            'nama_pelanggan' => $row['nama_pelanggan'],
            'alamat' => $row['alamat'],
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
