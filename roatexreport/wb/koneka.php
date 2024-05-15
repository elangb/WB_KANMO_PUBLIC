<?php
// Konfigurasi koneksi
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Konfigurasi koneksi
$servername = "sip.uidesk.id";
$username = "uidesk";
$password = "Uidesk123!";
$dbname = "asteriskcdrdb";
$port = "3306"; // Port default untuk MariaDB

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Melakukan sesuatu dengan koneksi

// Menutup koneksi
$conn->close();
?>
