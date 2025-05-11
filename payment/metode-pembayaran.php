<?php

namespace Midtrans;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
include("../include/koneksi.php");

$queryP = $koneksi->query("SELECT * FROM tbl_pgate WHERE id_pgat=1");
$tokenP = $queryP->fetch_assoc();
$clienttKey = $tokenP['tclientkey'];
$serverrKey = $tokenP['tserverkey'];

// can find in Merchant Portal -> Settings -> Access keys
Config::$serverKey = $serverrKey;

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

// Uncomment for production environment
Config::$isProduction = true;

// Uncomment to enable sanitization
Config::$isSanitized = true;

// Uncomment to enable 3D-Secure
Config::$is3ds = true;

$name = $_POST['name_user'];
$id_tagihan = $_POST['id_tagihan'];
$harga = $_POST['harga'];
$nama_paket = $_POST['nama_paket'];
$telp = $_POST['no_telp'];

// Required
$transaction_details = array(
    'order_id' => $id_tagihan . '-' . time(),
    'gross_amount' => $harga,
);

// Optional
$item_details = array(
    'id' => $id_tagihan,
    'price' => $harga,
    'quantity' => 1,
    'name' => $nama_paket
);

// Optional
$customer_details = array(
    'first_name'    => $name,
    'phone'         => $telp,
);

// Fill SNAP API parameter
$params = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => array($item_details),
);

try {
    // Get Snap Payment Page URL
    $paymentUrl = Snap::createTransaction($params)->redirect_url;

    // Redirect to Snap Payment Page
    header('Location: ' . $paymentUrl);
    exit();
} catch (\Exception $e) {
    echo $e->getMessage();
}

function printExampleWarningMessage()
{
    if (strpos(Config::$serverKey, 'your ') != false) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
        die();
    }
}
