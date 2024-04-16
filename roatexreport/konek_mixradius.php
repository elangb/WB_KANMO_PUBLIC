<?php
// Konfigurasi koneksi
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Konfigurasi koneksi

// Konfigurasi koneksi
$hostname = "149.28.156.138";
$database = "mixradius_radDB";
$username = "103.79.131.74";
$password = "qcXRAlD7HBfD9B1Z";
$connect = mysqli_connect($hostname, $username, $password, $database);


// Membuka koneksi
//$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($connect->connect_error) {
    die("Koneksi gagal: " . $connect->connect_error);
}

// Melakukan operasi database

// Menutup koneksi
$connect->close();
?>
