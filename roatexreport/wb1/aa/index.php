<?php
include('Net/SSH2.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$key ="Zimam@030622!!";
$ssh = new Net_SSH2('sip.uidesk.id', 22);   // Domain or IP
if (!$ssh->login('root', $key))   exit('Login Failed'); 
//echo $ssh->exec('pwd');
/*$get10010= $ssh->exec('sudo asterisk -x "sip show peers" | grep "10010"');
$parts10010 = preg_split('/\s(?=OK\b)/', $get10010);
if (isset($parts10010[1])) {
    // Lakukan sesuatu dengan $array['key']
    $AgentNya = explode(" ", $parts10010[0]);
    // Menampilkan hasil
    echo $AgentNya[0]."-".$parts10010[1];
} else {
    // Indeks tidak ditemukan
     // Pisahkan string menjadi array berdasarkan spasi
    $parts10010 = explode(" ", $parts10010[0]);
    // Menampilkan hasil
    echo $parts10010[0]."-".$parts10010[7];
}*/

$numbers = array(10010, 10011, 10012, 10013, 10014, 10015, 10016, 10017, 10018, 10019, 10020);

foreach ($numbers as $number) {
    $get10011= $ssh->exec('sudo asterisk -x "sip show peers" | grep '.$number.'');
    $partsOK = preg_split('/\s(?=OK\b)/', $get10011);
    $partsUNREACHABLE = preg_split('/\s(?=UNREACHABLE\b)/', $get10011);
    $partsUNKNOWN = preg_split('/\s(?=UNKNOWN\b)/', $get10011);
    if (isset($partsOK[1])) {
        // Lakukan OK
        $AgentNya = explode(" ", $partsOK[0]);
        // Menampilkan hasil
        echo $AgentNya[0]."-".$AgentNya[1]."-".$partsOK[1]."<br>";
    }else if (isset($partsUNREACHABLE[1])) {
        // Lakukan OK
        $AgentNya = explode(" ", $partsUNREACHABLE[0]);
        // Menampilkan hasil
        echo $AgentNya[0]."-".$AgentNya[1]."-".$partsUNREACHABLE[1]."<br>";
    }else if (isset($partsUNKNOWN[1])) {
        // Lakukan OK
        $AgentNya = explode(" ", $partsUNKNOWN[0]);
        // Menampilkan hasil
        echo $AgentNya[0]."-".$AgentNya[1]."-".$partsUNKNOWN[1]."<br>";
    }
}


?>