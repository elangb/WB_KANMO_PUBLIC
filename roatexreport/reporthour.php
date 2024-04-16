<?php
ini_set('display_errors', 1);//Atauerror_reporting(E_ALL && ~E_NOTICE);
// Membuat koneksi
$conn = new mysqli("sip.uidesk.id","root","Zimam@030622!!","asteriskcdrdb");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk memilih data dari tabel
$sql = "SELECT 
    CONCAT(LPAD(hour_value, 2, '0'), ':00') AS Hour,
    COUNT(asteriskcdrdb.cdr.calldate) AS Total, COALESCE(SEC_TO_TIME(SUM(billsec)),0) AS seconds
FROM 
    qstats.hour_helper
LEFT JOIN 
    asteriskcdrdb.cdr ON HOUR(asteriskcdrdb.cdr.calldate) = hour_value
AND 
    DATE(asteriskcdrdb.cdr.calldate) = '2024-01-30'
AND
( substring(dstchannel,1,locate('-',dstchannel,1)-1) IN ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016',
   'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029') OR substring(channel,1,locate('-',channel,1)-1) IN ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016',
   'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029')) 
GROUP BY 
    hour_value
ORDER BY 
    hour_value;";
$result = $conn->query($sql);

// Mengecek apakah ada baris yang dikembalikan
if ($result->num_rows > 0) {
    // Menampilkan data dalam tabel HTML
    echo "<table border='1'><tr><th>ID</th><th>Nama</th><th>Alamat</th></tr>";
    // Output data setiap baris
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["Hour"]. "</td><td>" . $row["Total"]. "</td><td>" . $row["seconds"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data yang ditemukan";
}
// Menutup koneksi
$conn->close();
?>