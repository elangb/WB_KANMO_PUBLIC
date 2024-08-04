<?php
  include('Net/SSH2.php');
  ini_set('display_errors', 0);
  ini_set('display_startup_errors', 0);
    //error_reporting(E_ALL);
  $key ="Uid35k32!J4y4J4y4";
  $ssh1 = new Net_SSH2('pbx.uidesk.id', 3389);   // Domain or IP
  if (!$ssh1->login('root', $key))   exit('Login Failed'); 
  if ($_GET['param'] =='KANMO'){
  //$numbers1 = array(101010,101011,101012,101013,201010,201011, 201012, 201013, 201014, 201015,301010, 301011, 301012, 301013, 301014, 301015);
  $numbers1 = array(201010,201011, 201012, 201013, 201014, 201015);
  }else{
    $numbers1 = array(101010,101011,101012,101013);
  }
  $ready=0;
  $notready=0;
  $get100111result=0;
  $myObj = new stdClass();
  $outputArray = array();
  
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
      //echo $AgentNya."-".$partsOK1[1]. "<br>";
      //$get100111result.=$get100111;
      //$myObj->extension = $AgentNya;
      //$myObj->state = $partsOK1[1];
      //$myObj->customer = "Cantas";
      $extNya = explode("/", $AgentNya[0]);
      $string = $partsOK1[1];
      $twoChars = substr($string, 0, 2);
      if ($twoChars === "OK"){

      }else{
        $twoChars = substr($string, 0, 7);
      }
      $outputArray['DataDetail'][] = array(
        'extension' => $extNya[0],
        'state' => $twoChars,
        'JumlahReady' => $ready,
        'JumlahNotReady' => $notready
     
    );

  }
  //echo $get100111result;
 

    $myJSON = json_encode($outputArray);

    echo $myJSON;

    /*$dataArray = json_decode($myJSON, true);

    // Mendapatkan jumlah elemen dalam array
    $count = count($dataArray);

    // Mendapatkan nilai dari kunci "JumlahReady" pada objek terakhir dalam array
    $lastJumlahReady = $dataArray[$count - 1]['JumlahReady'];

    echo "Jumlah Ready pada objek terakhir: " . $lastJumlahReady;*/
?>
	
   