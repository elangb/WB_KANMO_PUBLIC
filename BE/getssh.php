<?php
  include('Net/SSH2.php');
  ini_set('display_errors', 0);
  ini_set('display_startup_errors', 0);
   
  $key ="P@ssword1234!!@@";
  $ssh1 = new Net_SSH2('call.ahu-mobile.com', 8072);   
  if (!$ssh1->login('root', $key))   exit('Login Failed'); 
  
  $numbers1 = array(10010);
  $ready=0;
  $notready=0;
  $get100111result=0;
  $myObj = new stdClass();
  $outputArray = array();
  
   
   $memberData= $ssh1->exec('sudo asterisk -x "queue show 9000"');
   
   $rows = explode("\n", $memberData);
      $in_call_count = 0;
      $in_callwait_count = 0;
      $in_ready_count = 0;
      $in_unavailable_count = 0;
     
      foreach ($rows as $row) {
     
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
      
  
      $outputArray['DataDetail'][] = array(
          'ACD-IN' => $in_call_count,
          'QUE' => $in_callwait_count,
          'READY' => $in_ready_count,
          'UNAVAILABLE' => $in_unavailable_count
      );
     
      $myJSON = json_encode($outputArray);
      $data = json_decode($myJSON, true);
      echo json_encode($data, JSON_PRETTY_PRINT);

    
?>
	
   