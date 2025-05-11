<?php
// Isi HTML invoice yang ingin Anda konversi ke PDF
$html = file_get_contents('cetak.php');

// Simpan HTML ke file sementara
$tmpHtmlFile = tempnam(sys_get_temp_dir(), 'html');
file_put_contents($tmpHtmlFile, $html);

// Tentukan nama file output PDF
$outputPdfFile = 'bukti_bayar.pdf';

// Eksekusi perintah shell untuk mengonversi HTML ke PDF menggunakan wkhtmltopdf
$command = "wkhtmltopdf $tmpHtmlFile $outputPdfFile";
exec($command);

// Outputkan hasil PDF ke browser
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="bukti_bayar.pdf"');
readfile($outputPdfFile);

// Hapus file sementara HTML dan output PDF
unlink($tmpHtmlFile);
unlink($outputPdfFile);
