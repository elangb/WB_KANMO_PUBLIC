<?php
include('Net/SSH2.php');
/*
$ssh = new Net_SSH2('sip.uidesk.id');
if (!$ssh->login('root', 'zimam@0306!!')) {
    exit('Login Failed');
}

echo $ssh->exec('pwd');
echo $ssh->exec('ls -la');*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$key ="zimam@0306!!";
  /* ### if using PrivateKey ### 
  use phpseclib\Crypt\RSA;
  $key = new RSA();
  $key->load(file_get_contents('private-key.ppk'));
  */

$ssh = new Net_SSH2('sip.uidesk.id', 3389);   // Domain or IP
if (!$ssh->login('root', $key))   exit('Login Failed'); 

//echo $ssh->exec('pwd');
$string= $ssh->exec('sudo asterisk -x "sip show peers" | grep "10010"');
/*
//$teks = "Name/username Host Dyn Forcerport Comedia ACL Port Status 1001/1001 (Unspecified) D Yes Yes A 0 UNKNOWN 10010/10010 (Unspecified) D Yes Yes A 0 UNKNOWN 10011/10011 (Unspecified) D Yes Yes A 0 UNKNOWN 10012/10012 (Unspecified) D Yes Yes A 0 UNKNOWN 10013/10013 (Unspecified) D Yes Yes A 0 UNKNOWN 10014/10014 (Unspecified) D Yes Yes A 0 UNKNOWN 10015/10015 (Unspecified) D Yes Yes A 0 UNKNOWN 10016/10016 (Unspecified) D Yes Yes A 0 UNKNOWN 10017 (Unspecified) D Yes Yes A 0 UNKNOWN 10018 (Unspecified) D Yes Yes A 0 UNKNOWN 10019 (Unspecified) D Yes Yes A 0 UNKNOWN 10020 (Unspecified) D Yes Yes A 0 UNKNOWN 101010/101010 (Unspecified) D Yes Yes A 0 UNKNOWN 101011 (Unspecified) D Yes Yes A 0 UNKNOWN 101012 (Unspecified) D Yes Yes A 0 UNKNOWN TR-KANMO 103.129.205.228 No Yes 5060 OK (2 ms) TR-PROVIDER 172.16.77.3 No Yes 5060 OK (1 ms) TR-ROATEX 202.43.170.71 No Yes 5060 OK (1 ms) 18 sip peers [Monitored: 3 online, 15 offline Unmonitored: 0 online, 0 offline]";
$teks =str_replace(' Description', '', $abc);
// Ubah teks menjadi array kata-kata
$arrayKata = explode(" ", preg_replace("/ {2,}/", " ", $teks));

// Jumlah kata per baris
$kataPerBaris = 8;

// Bagi kata menjadi baris
$baris = array_chunk($arrayKata, $kataPerBaris);

// Tampilkan hasilnya
foreach ($baris as $index => $kataBaris) {
    $string= implode(" ", $kataBaris);
}*/
//echo $abc;
//$string = "10010/10010 122.50.7.234 D Yes Yes A 8892 OK (11 ms)";

// Pisahkan string dengan regular expression, mempertahankan kata "OK"
$parts = preg_split('/\s(?=OK\b)/', $string);
//print_r($parts);
//echo $parts[1];
// Menampilkan hasil
//print_r($parts);
if (isset($parts[1])) {
    // Lakukan sesuatu dengan $array['key']
    $AgentNya = explode(" ", $parts[0]);
    // Menampilkan hasil
    echo $AgentNya[0]."-".$parts[1];
} else {
    // Indeks tidak ditemukan
     // Pisahkan string menjadi array berdasarkan spasi
    $parts = explode(" ", $parts[0]);
    // Menampilkan hasil
    echo $parts[0]."-".$parts[7];
}


?>