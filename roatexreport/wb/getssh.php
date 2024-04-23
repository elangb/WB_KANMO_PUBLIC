<?php
include('Net/SSH2.php');
                            ini_set('display_errors', 0);
                            ini_set('display_startup_errors', 0);
                            //error_reporting(E_ALL);
                            $key ="Zimam@030622!!";
 $ssh1 = new Net_SSH2('sip.uidesk.id', 22);   // Domain or IP
if (!$ssh1->login('root', $key))   exit('Login Failed'); 
$numbers1 = array(10010,10011,10012,10013,10014,10015, 10016, 10017, 10018, 10019,10020, 10021, 10022, 10023, 10024, 10025, 10026, 10027, 10028, 10029);
$ready=0;
$notready=0;
$get100111result=0;
foreach ($numbers1 as $number1) {
    $get100111= $ssh1->exec('sudo asterisk -x "sip show peers" | grep '.$number1.'');
    $partsOK1 = preg_split('/\s(?=OK\b)/', $get100111);
    $partsUNREACHABLE1 = preg_split('/\s(?=UNREACHABLE\b)/', $get100111);
    $partsUNKNOWN1 = preg_split('/\s(?=UNKNOWN\b)/', $get100111);
    if (isset($partsOK1[1])) {
       $ready=$ready+1;
    }else if (isset($partsUNREACHABLE1[1])) {
       $notready=$notready+1; 
    }else if (isset($partsUNKNOWN1[1])) {
        $notready=$notready+1;
    }
	$AgentNya = explode(" ", $partsUNREACHABLE1[0]);
    echo $AgentNya."-".$partsOK1[1]. "<br>";;
	//$get100111result.=$get100111;
}
//echo $get100111result;
// Bagi string setiap 8 spasi
	$chunks = preg_split('/\s{8}/', $get100111result);

	foreach ($chunks as $chunk) {
		//echo $chunk . "<br>";
		$partsOK1 = preg_split('/\s(?=OK\b)/', $get100111result);
		$partsUNREACHABLE1 = preg_split('/\s(?=UNREACHABLE\b)/', $get100111result);
		$partsUNKNOWN1 = preg_split('/\s(?=UNKNOWN\b)/', $get100111result);
		if (isset($partsOK1[1])) {
		   $ready=$ready+1;
		}else if (isset($partsUNREACHABLE1[1])) {
		   $notready=$notready+1; 
		}else if (isset($partsUNKNOWN1[1])) {
			$notready=$notready+1;
		}
	}
	
?>