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
        $get60011= $ssh1->exec('sudo asterisk -x "queue show 60010"');
        $get60012= $ssh1->exec('sudo asterisk -x "queue show 60012"');
        //$outputArray['DataDetail'][] = array(
         // 'dataque' => $get100111
      //);
  
    }
    //echo $get100111result;
   
  
      //echo $get100111;
  
      // Mengambil data dari value "Member:"
      $memberData = "";
      foreach ($data['DataDetail'] as $detail) {
          $detailText = $detail['dataque'];
          //echo $memberData;
          $startIndex = strpos($detailText, 'Members:');
          if ($startIndex !== false) {
              $memberData = substr($detailText, $startIndex + strlen('Members:'));
              
              break;
          }
      }
      
      //echo $memberData; // Output data member
      $memberData=$get60011;
      $memberData6002=$get60012;
      //echo $memberData;
      //echo $memberData6002;
      /*$dataArray = explode("\n", $memberData);
  
      // Iterasi dan mencetak setiap baris
      foreach ($dataArray as $line) {
          //echo $line . "<br>";
      }*/
      // Split the data into rows
      $rows = explode("\n", $memberData);
      $in_call_count = 0;
      $in_callwait_count = 0;
      $in_ready_count = 0;
      $in_unavailable_count = 0;
      // Iterate through each row
      foreach ($rows as $row) {
          // Check if the row contains "(in call)"
          if (strpos($row, "(in call)") !== false) {
              $in_call_count++;
          }
          if (strpos($row, "wait:") !== false) {
              $in_callwait_count++;
          }
          if (strpos($row, "Not in use") !== false) {
              $in_ready_count++;
          }
          if (strpos($row, "Unavailable") !== false) {
              $in_unavailable_count++;
          }
      }
      //Not in use <- ini Ready
      //Unavailable <- ini nggk masuk
      $rows6002 = explode("\n", $memberData6002);
      $in_call_count6002 = 0;
      $in_callwait_count6002 = 0;
      $in_ready_count6002 = 0;
      $in_unavailable_count6002 = 0;
      // Iterate through each row
      foreach ($rows6002 as $row6002) {
          // Check if the row contains "(in call)"
          if (strpos($row6002, "(in call)") !== false) {
              $in_call_count6002++;
          }
          if (strpos($row6002, "wait:") !== false) {
              $in_callwait_count6002++;
          }
          if (strpos($row6002, "Not in use") !== false) {
              $in_ready_count6002++;
          }
          if (strpos($row6002, "Unavailable") !== false) {
              $in_unavailable_count6002++;
          }
      }
  
      //echo "ACD-IN : " . $in_call_count." QUE : " .$in_callwait_count;
  
      $outputArray['DataDetail'][] = array(
          'ACD-IN' => $in_call_count+$in_call_count6002,
          'QUE' => $in_callwait_count+$in_callwait_count6002,
          'READY' => $in_ready_count+$in_ready_count6002,
          'UNAVAILABLE' => $in_unavailable_count+$in_unavailable_count6002
      );
      //Bisa di gabung di pisah berdasarkan kanmo/nespresso
      /*$outputArray['DataDetail'][] = array(
          'ACD-IN' => $in_call_count+$in_call_count6002,
          'QUE' => $in_callwait_count+$in_callwait_count6002,
          'READY' => $in_ready_count+$in_ready_count6002,
          'UNAVAILABLE' => $in_unavailable_count+$in_unavailable_count6002
      );*/
      $myJSON = json_encode($outputArray);
      $data = json_decode($myJSON, true);
      echo json_encode($data, JSON_PRETTY_PRINT);

    
?>
	
   