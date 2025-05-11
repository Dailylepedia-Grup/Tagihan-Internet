<?php
// Assuming $backup_date is the specific date you want to use for selecting the file
$backup_date = "2024-01-26"; // Replace this with your actual date

// Form the file path based on the date
$backup_file_path = "backup_db/backup_" . $backup_date . ".sql";

// Check if the file exists
if (file_exists($backup_file_path)) {
    // Read the file content
    $sqlScript = file_get_contents($backup_file_path);

    // Inject the file content and file name into the JavaScript
    echo '<script>
            document.getElementById("downloadscsr").onclick = function() {
              var fileContent = ' . json_encode($sqlScript) . ';
              var fileName = "' . basename($backup_file_path) . '";

              var blob = new Blob([fileContent], {
                type: "text/plain"
              });
              var link = document.createElement("a");

              link.href = window.URL.createObjectURL(blob);
              link.download = fileName;

              document.body.appendChild(link);

              link.click();

              document.body.removeChild(link);
            };
          </script>';
} else {
    echo 'File not found for the specified date.';
}
