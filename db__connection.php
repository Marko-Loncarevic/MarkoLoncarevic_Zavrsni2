<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

$server   = 'localhost';
$username = 'root';
$password = '';
$database = 'rentacar';

$db = mysqli_connect($server, $username, $password, $database);

if (!$db) {
    error_log("DB connection failed: " . mysqli_connect_error());
    http_response_code(500);
    die("Greška pri povezivanju s bazom podataka. Pokušajte ponovo.");
}

mysqli_set_charset($db, 'utf8mb4');
?>