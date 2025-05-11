<?php
require('../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include("../../include/koneksi.php");

// Periksa koneksi database
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Query data pelanggan
// $query = "SELECT id_pelanggan, kode_pelanggan, nik, nama_pelanggan, no_telp, paket, ip_address, tgl_pemasangan, jatuh_tempo, location  FROM tb_pelanggan";

$query = "SELECT tb_pelanggan.id_pelanggan, tb_pelanggan.kode_pelanggan, tb_pelanggan.nik, tb_pelanggan.nama_pelanggan, tb_pelanggan.no_telp, tb_paket.nama_paket, 
    tb_pelanggan.ip_address, 
    tb_pelanggan.tgl_pemasangan, 
    tb_pelanggan.jatuh_tempo, 
    tb_pelanggan.location
FROM tb_pelanggan INNER JOIN tb_paket ON tb_pelanggan.paket = tb_paket.id_paket";

$result = $koneksi->query($query);

// Periksa jika query berhasil
if (!$result) {
    die("Query gagal: " . $koneksi->error);
}

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
// Header kolom
$sheet->setCellValue('A1', 'ID Pelanggan');
$sheet->setCellValue('B1', 'Kode Pelanggan');
$sheet->setCellValue('C1', 'NIK');
$sheet->setCellValue('D1', 'Nama Pelanggan');
$sheet->setCellValue('E1', 'No Telepon');
$sheet->setCellValue('F1', 'Paket');
$sheet->setCellValue('G1', 'IP');
$sheet->setCellValue('H1', 'Tanggal Pemasangan');
$sheet->setCellValue('I1', 'Jatuh Tempo');
$sheet->setCellValue('J1', 'Titik Lokasi');

// Menambahkan style bold pada header kolom
$sheet->getStyle('A1:J1')->getFont()->setBold(true);

// Isi data
$rowNumber = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNumber, $row['id_pelanggan']);
    $sheet->setCellValue('B' . $rowNumber, $row['kode_pelanggan']);
    $sheet->setCellValue('C' . $rowNumber, $row['nik']);
    $sheet->setCellValue('D' . $rowNumber, $row['nama_pelanggan']);
    $sheet->setCellValue('E' . $rowNumber, $row['no_telp']);
    $sheet->setCellValue('F' . $rowNumber, $row['nama_paket']);
    $sheet->setCellValue('G' . $rowNumber, $row['ip_address']);
    $sheet->setCellValue('H' . $rowNumber, $row['tgl_pemasangan']);
    $sheet->setCellValue('I' . $rowNumber, $row['jatuh_tempo']);
    $sheet->setCellValue('J' . $rowNumber, $row['location']);
    $rowNumber++;
}

// Set header untuk download file Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="data_pelanggan_ezbill.xlsx"');

// Tulis file ke output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
