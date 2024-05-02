<?php
$mysqli = new mysqli("pbx.uidesk.id","root","Uid35k32!Uid35k32!J4y4","qstats");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}


$add_query = "";

$three_days_ago = date("Y-m-d 00:00:00", strtotime("-3 days", time()));
/*$result = $mysqli -> query("select * from(
select 'CallReceived' TypeNya,COUNT(*) as ValNya from asteriskcdrdb.cdr where DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE()
Union
select 'CallAnswered' TypeNya,COUNT(*) as ValNya from asteriskcdrdb.cdr where DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()  and disposition='ANSWERED' 
Union
select 'CallAbandoned' TypeNya,COUNT(*) as ValNya from asteriskcdrdb.cdr where DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE()  and (disposition='NO ANSWER' or disposition='BUSY') 
) as a");*/


// if (isset($_GET['param'])) {
//     $paramValue = $_GET['param'];
//     // Now you can use $paramValue in your PHP logic
//     echo json_encode(array("message" => "Parameter received: " . $paramValue));
// } else {
//     echo json_encode(array("error" => "Parameter not provided"));
// }

$paramValue ='' ;
//die($paramValue);
if ($_GET['param'] =='NESPRESSO')
  $paramValue ='60010,60011';
else
  $paramValue ='60012,60013';

  
$sqlNya="select agent as AgentName,COUNT(id) as IncomingCall,0 as OutgoingCall,SEC_TO_TIME(SUM(waittime)) as WaitingTime,SEC_TO_TIME(SUM(talktime)) as TalkingTime,'Ready' as StatusAgent from queue_stats_mv where DATE(datetime)=DATE(NOW()) and queue in($paramValue) and agent <>'NONE' group by agent order by id desc;";
$result = $mysqli -> query($sqlNya);
//die($sqlNya);
$data = [];
while ($row = $result->fetch_assoc()) {
   
    $data['DataDetail'][] = $row;
}

echo json_encode($data);
?>
