<?php

// Database configuration
include("../../include/koneksi.php");

// Get connection object and set the charset
$conn = mysqli_connect($host, $username, $password, $database_name);
$conn->set_charset("utf8");

// Get All Table Names From the Database
$tables = array();
$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

// SQL script for structure and data
$sqlScript = "";

foreach ($tables as $table) {
    // Prepare SQLscript for creating table structure
    $query = "SHOW CREATE TABLE $table";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);

    $sqlScript .= "\n\n" . $row[1] . ";\n\n";

    // Prepare SQLscript for dumping data for each table
    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);

    $columnCount = mysqli_num_fields($result);

    for ($i = 0; $i < $columnCount; $i++) {
        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $columnCount; $j++) {
                $row[$j] = $row[$j];

                if (isset($row[$j])) {
                    $sqlScript .= '"' . $row[$j] . '"';
                } else {
                    $sqlScript .= '""';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
    }
    $sqlScript .= "\n";
}

// Specify the directory path for backup files
$backup_directory = 'backup_db/';

// Create a formatted date for the backup file name (e.g., 12-02-2001)
$formatted_date = date('Y-m-d');

$nama_db =  $database_name . " " . $formatted_date . '.sql';

// Save the SQL script to a backup file in the specified directory
$backup_file_name = $backup_directory  .  $database_name . " " . $formatted_date . '.sql';
$fileHandler = fopen($backup_file_name, 'w+');
$number_of_lines = fwrite($fileHandler, $sqlScript);

// Insert backup information into riwayat_backupdb table if not exists
$escaped_backup_file_name = mysqli_real_escape_string($conn, $nama_db);

// Check if data already exists
$check_query = "SELECT * FROM riwayat_backupdb WHERE nama_db = '$escaped_backup_file_name' AND tanggal = '$formatted_date'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    // Data does not exist, perform INSERT
    $insert_query = "INSERT INTO riwayat_backupdb (nama_db, tanggal) VALUES ('$escaped_backup_file_name', '$formatted_date')";
    mysqli_query($conn, $insert_query);

    echo "

    <script>
        setTimeout(function() {
            swal({
                title: 'Data Billing',
                text: 'Berhasil Di Cadangkan!',
                type: 'success'
            }, function() {
                window.location = '?page=backup';
            });
        }, 300);
    </script>

";
} else {
    echo "

    <script>
        setTimeout(function() {
            swal({
                title: 'Data Billing',
                text: 'Sudah di Cadangkan',
                type: 'error'
            }, function() {
                window.location = '?page=backup';
            });
        }, 300);
    </script>

";
}


fclose($fileHandler);
