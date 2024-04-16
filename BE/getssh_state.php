<?php
  include('Net/SSH2.php');
  ini_set('display_errors', 0);
  ini_set('display_startup_errors', 0);
    //error_reporting(E_ALL);
    $key ="Uid35k32!J4y4J4y4";
    $ssh1 = new Net_SSH2('pbx.uidesk.id', 3389);   // Domain or IP
    if (!$ssh1->login('root', $key))   exit('Login Failed'); 
  $numbers1 = array(10010);
  $ready=0;
  $notready=0;
  $get100111result=0;
  $myObj = new stdClass();
  $outputArray = array();
  
  foreach ($numbers1 as $number1) {
      $get100111= $ssh1->exec('sudo asterisk -x "queue show 60011"');
      
      //$outputArray['DataDetail'][] = array(
       // 'dataque' => $get100111
   // );

  }
  //echo $get100111result;
 

    

    // Mengambil data dari value "Member:"
    $memberData = "";
    foreach ($data['DataDetail'] as $detail) {
        $detailText = $detail['dataque'];
        $startIndex = strpos($detailText, 'Members:');
        if ($startIndex !== false) {
            $memberData = substr($detailText, $startIndex + strlen('Members:'));
            break;
        }
    }
    $data_call = "9000 has 1 calls (max unlimited) in 'rrordered' strategy (2s holdtime, 23s talktime), W:0, C:3, A:3, SL:100.0%, SL2:100.0% within 60s
    Members:
       Demmy Fitra (Local/10011@from-queue/n from hint:10011@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40m (in call)[0m) has taken 8 calls (last was 14264 secs ago)
         Syawalika Imanda (Local/10012@from-queue/n from hint:10012@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken no calls yet
         Alvin Juliardy Santofia (Local/10014@from-queue/n from hint:10014@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40m (in call)[0m) has taken 7 calls (last was 14482 secs ago)
         Jauhar Andre (Local/10017@from-queue/n from hint:10017@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 3 calls (last was 105757 secs ago)
         Antony Tambunan (Local/10019@from-queue/n from hint:10019@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 6 calls (last was 15539 secs ago)
         Ryanti Rahmawati (Local/10025@from-queue/n from hint:10025@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 9 calls (last was 12750 secs ago)
         Jayanti Setiawati (Local/10026@from-queue/n from hint:10026@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 3 calls (last was 275430 secs ago)
    Callers:
       1. SIP/10001-000000a2 (wait: 0:11, prio: 0)";
    //echo $memberData; // Output data member
    $dataArray = explode("\n", $memberData);

    // Iterasi dan mencetak setiap baris
    foreach ($dataArray as $line) {
        //echo $line . "<br>";
    }
    // Split the data into rows
    $rows = explode("\n", $memberData);
    $in_call_count = 0;
    $in_callwait_count = 0;
    // Iterate through each row
    foreach ($rows as $row) {
        // Check if the row contains "(in call)"
        if (strpos($row, "(in call)") !== false) {
            $in_call_count++;
        }
        if (strpos($row, "wait:") !== false) {
            $in_callwait_count++;
        }
    }
    //echo "ACD-IN : " . $in_call_count." QUE : " .$in_callwait_count;

    $outputArray['DataDetail'][] = array(
        'ACD-IN' => $in_call_count,
        'QUE' => $in_callwait_count
    );
    $myJSON = json_encode($outputArray);
    $data = json_decode($myJSON, true);
    echo $myJSON;

    /*$dataArray = json_decode($myJSON, true);

    // Mendapatkan jumlah elemen dalam array
    $count = count($dataArray);

    // Mendapatkan nilai dari kunci "JumlahReady" pada objek terakhir dalam array
    $lastJumlahReady = $dataArray[$count - 1]['JumlahReady'];

    echo "Jumlah Ready pada objek terakhir: " . $lastJumlahReady;*/
?>
	
   