<?php
ini_set("error_reporting", E_ALL);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('GMT');
$mysqli = new mysqli("pbx.uidesk.id","root","Uid35k32!Uid35k32!J4y4","asteriskcdrdb");
/*

user : root
pass : zimam@0306!!
user : uidesk
pass : Uidesk123!
*/
// Check connection
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }
  
  //OUTBOUND
  // First outbound
  $chanfield      = "channel";
  $otherchanfield = "dstchannel";
  $rep_title      = "Incoming / Outgoing";

  $paramValue ='' ;
//die($paramValue);


  $query = "SELECT substring($chanfield,1,locate(\"-\",$chanfield,length($chanfield)-8)-1) AS chan1,";
  $query.= "billsec,duration,duration-billsec as ringtime,src,dst,calldate,disposition,accountcode FROM asteriskcdrdb.cdr ";
  $query.= "WHERE DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE() AND (duration-billsec) >=0 ";
  
  if ($_GET['param'] =='NESPRESSO')
    $query.= "HAVING chan1 in ('SIP/101010','SIP/101011','SIP/101012','SIP/101013') order by null";
  else
    $query.= "HAVING chan1 in ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014') order by null";
  

  $total_calls = 0;
  $total_bill  = 0;
  $total_ring  = 0;

  //while ($res->fetchInto($row, DB_FETCHMODE_ASSOC)) {
    $result = $mysqli -> query($query);
  
      
  
    //while ($res->fetchInto($row, DB_FETCHMODE_ASSOC)) {
        while ($row = $result->fetch_assoc()) {

      $row['accountcode']="Default";

      
      $ringing[$row['accountcode']][$row['chan1']]+=$row['ringtime'];
      $ringing_outbound[$row['accountcode']][$row['chan1']]+=$row['ringtime'];
      $total_bill_outbound+=$row['billsec'];
      $total_ring_outbound+=$row['ringtime'];
      $total_calls_outbound++;


      
  }





  //INBOUND
  $chanfield      = "dstchannel";
      $otherchanfield = "channel";
   
      $query = "SELECT substring($chanfield,1,locate(\"-\",$chanfield,length($chanfield)-8)-1) AS chan1,";
      $query.= "billsec,duration,duration-billsec as ringtime,src,dst,calldate,disposition,accountcode FROM asteriskcdrdb.cdr ";
      $query.= "WHERE DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE() AND (duration-billsec) >=0  ";

      if ($_GET['param'] =='NESPRESSO')
        $query.= "HAVING chan1 in ('SIP/101010','SIP/101011','SIP/101012','SIP/101013') order by null";
      else
        $query.= "HAVING chan1 in ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014') order by null";
    
     // $query.= "HAVING chan1 in ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014','SIP/101010','SIP/101011','SIP/101012','SIP/101013')  order by null";
      //echo $query;
      $result = $mysqli -> query($query);
  
      
  
      //while ($res->fetchInto($row, DB_FETCHMODE_ASSOC)) {
          while ($row = $result->fetch_assoc()) {
  
          $row['accountcode']="Default";
  
         
              $group_bill_inbound[$row['accountcode']]  = 0;
              $group_ring_inbound[$row['accountcode']]  = 0;
              $group_calls_inbound[$row['accountcode']] = 0;
        
  
          if(!isset($billsec[$row['accountcode']][$row['chan1']])) {
              $billsec[$row['accountcode']][$row['chan1']]      = 0;
              $duration[$row['accountcode']][$row['chan1']]     = 0;
              $ringing_inbound[$row['accountcode']][$row['chan1']]      = 0;
              $ringing[$row['accountcode']][$row['chan1']]      = 0;
              $number_calls_inbound[$row['accountcode']][$row['chan1']] = 0;
              $missed_inbound[$row['accountcode']][$row['chan1']]       = 0;
          }
  
          if(!isset($number_calls_outbound[$row['accountcode']][$row['chan1']])) {
              $number_calls_outbound[$row['accountcode']][$row['chan1']]=0;
          }
  
          if(!isset($number_calls_inbound[$row['accountcode']][$row['chan1']])) {
              $number_calls_inbound[$row['accountcode']][$row['chan1']]=0;
          }
  
          if(!isset($number_calls_outbound[$row['accountcode']][$row['chan1']])) {
              $number_calls_outbound[$row['accountcode']][$row['chan1']]=0;
          }
  
          if(!isset($number_calls[$row['accountcode']][$row['chan1']])) {
              $number_calls[$row['accountcode']][$row['chan1']]=0;
          }
  
          $billsec[$row['accountcode']][$row['chan1']]  += $row['billsec'];
          $duration[$row['accountcode']][$row['chan1']] += $row['duration'];
          $number_calls_inbound[$row['accountcode']][$row['chan1']]++;
          $number_calls[$row['accountcode']][$row['chan1']]++;
  
          if(!isset($missed_inbound[$row['accountcode']][$row['chan1']])) { $missed_inbound[$row['accountcode']][$row['chan1']]=0; }
  
          $ringing_inbound[$row['accountcode']][$row['chan1']]+=$row['ringtime'];
          $ringing[$row['accountcode']][$row['chan1']]+=$row['ringtime'];
          $total_bill_inbound+=$row['billsec'];
          $total_ring_inbound+=$row['ringtime'];
          $total_calls_inbound++;
  
          $group_bill_inbound[$row['accountcode']]+=$row['billsec'];
          $group_ring_inbound[$row['accountcode']]+=$row['ringtime'];
          $group_calls_inbound[$row['accountcode']]++;
  
          $disposition = $row['disposition'];
  
          if(!isset($missed[$row['accountcode']][$row['chan1']])) { $missed[$row['accountcode']][$row['chan1']]=0;}
          if($disposition<>"ANSWERED" ) {
              $missed_inbound[$row['accountcode']][$row['chan1']]++;
              $missed[$row['accountcode']][$row['chan1']]++;
          }
      }
  
      $total_calls = $total_calls_inbound;
      $total_ring  = $total_ring_inbound + $total_ring_outbound;
      $total_bill  = $total_bill_inbound + $total_bill_outbound;
  
      $group_calls['Default'] = $group_calls_inbound[$row['accountcode']] + $group_calls_outbound[$row['accountcode']];
      $group_ring['Default']  = $group_ring_inbound[$row['accountcode']] + $group_ring_outbound[$row['accountcode']];
      $group_bill['Default']  = $group_bill_inbound[$row['accountcode']] + $group_bill_outbound[$row['accountcode']];
  
      if($total_calls > 0) {
          $avg_ring_full = $total_ring / $total_calls;
      } else {
          $avg_ring_full = 0;
      }
  
      $avg_ring_full = number_format($avg_ring_full,0);
  
      $total_bill_print = $total_bill;//seconds2minutes($total_bill);
  
      //$start_parts = preg_split("/ /", $appconfig['start']);
      //$end_parts   = preg_split("/ /", $appconfig['end']);
  
  ?>
  
  <?php
  if($total_calls>0) {
  ?>
      
  
  <?php
  
  foreach($billsec as $idx=>$key) {
     
          //if(count($appconfig['departments'])>1) {
  
              $group_bill_print = $group_bill[$idx];
    
              if($group_calls[$idx]>0) {
                  $avg_ring_group = $group_ring[$idx] / $group_calls[$idx];
              } else {
                  $avg_ring_group = 0;
              }
  
              $avg_ring_group = number_format($avg_ring_group,0);
  
  
        // }
  
         
  
         $contador=0;
         $query1="";
         $query2="";
         $query3="";
  
         foreach($key as $chan=>$val) {
  
              $contavar = $contador +1;
              $cual = $contador % 2;
              if($cual>0) { $odd = " class='odd' "; } else { $odd = ""; }
  
              $nomuser=$appconfig['canals'][$chan];
  
              $nomissed = $number_calls[$idx][$chan] - $missed[$idx][$chan];
  
              $yesmissed   = $missed[$idx][$chan];
              $query1 .= "valA$contavar=$nomissed&valB$contavar=$yesmissed&var$contavar=$nomuser&";
              $query2 .= "val$contavar=".$val."&var$contavar=$nomuser&";
              $query3 .= "valA$contavar=".$number_calls_outbound[$idx][$chan]."&valB$contavar=".$number_calls_inbound[$idx][$chan]."&var$contavar=$nomuser&";
  
              $ring_time = $duration[$idx][$chan]-$val;
  
              if($number_calls[$idx][$chan]>0) {
                  $avg_ring_time = $ring_time / $number_calls[$idx][$chan];
                  if($nomissed>0) {
                      $avg_duration  = $val / $nomissed;
                  } else {
                      $avg_duration = 0;
                  }
              } else {
                  $avg_duration  = 0;
                  $avg_ring_time = 0;
              }
  
              $avg_duration = number_format($avg_duration,0);
              $avg_duration_print = $avg_duration;
              $avg_ring_time = number_format($avg_ring_time,2);
              $time_print = $duration[$idx][$chan];
  
              $bill_print = $val;
              if($number_calls[$idx][$chan] > 0) {
                  $percent_missed = $missed[$idx][$chan] * 100 / $number_calls[$idx][$chan];
              } else {
                  $percent_missed = 0;
              }
              $percent_missed = number_format($percent_missed,0)." "._('%');
  
              $complete_self = $_SERVER['REQUEST_URI'];
              $total_complete +=$nomissed;
              $totalMissed +=$missed[$idx][$chan];
             
              $totalMissedRate +=$percent_missed;
             
              $totalDurations +=$bill_print;
              $percentage_bill = $val * 100 / $total_bill;
              $percentage_bill = number_format($percentage_bill,2);
             
              $totalDurationsRate +=$percentage_bill;
             
              $totalAvgDurations +=$avg_duration_print;
              
              $totalRingTimes +=$ring_time;
             
  
  }
  
  }
  
  } // end if totalbill > 1
  class DataDetail {
     
      public $Total_Inbound_Calls;
      public $Total_Outbound_Calls;
      public $Total_Complete_Calls;
      public $Total_Times;
      public $Average_Inbound_Ring_Duration;   
      public $Total_Missed_Calls;
      public $Total_Missed_Rate_Calls;
      public $Total_Duration_Calls;
      public $Total_Duration_Rate_Calls;
      public $Avg_Duration_Calls;
      public $Total_Rings_Time;
      // Add more properties as needed
  }
  $object1 = new DataDetail();
      $object1->Total_Inbound_Calls = $total_calls;
      $object1->Total_Outbound_Calls = $total_calls_outbound;
      $object1->Total_Complete_Calls = $total_complete;
      $object1->Total_Times = $total_bill_print;
      $object1->Average_Inbound_Ring_Duration = $avg_ring_full;
      $object1->Total_Missed_Calls =  $totalMissed;
      $object1->Total_Missed_Rate_Calls =  $totalMissedRate;
      $object1->Total_Duration_Calls = $totalDurations;
      $object1->Total_Duration_Rate_Calls = $totalDurationsRate;
      $object1->Avg_Duration_Calls = $totalAvgDurations;
      $object1->Total_Rings_Time = $totalRingTimes;
     
      
     
     
      
     
      
      
      
      // Create the array mapping
      $data['DataDetail'] = array($object1);
      echo json_encode($data, JSON_PRETTY_PRINT);    

?>
