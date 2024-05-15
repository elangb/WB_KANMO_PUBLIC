<?php
ini_set("error_reporting", E_ALL);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('GMT');
$mysqli = new mysqli("202.43.173.61","matthew","supersecretpassword","asteriskcdrdb");
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


$add_query = "";

$three_days_ago = date("Y-m-d 00:00:00", strtotime("-3 days", time()));

/*$result = $mysqli -> query("select * from(
select 'CallReceived' TypeNya,COUNT(uniqueid) as jumlah from qstats.queue_stats_full where qname in ('2','3') and event='CONNECT' and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
Union
select 'CallAnswered' TypeNya,COUNT(*) as ValNya from qstats.queue_stats_mv where (queue='60012' or queue='60011') and event in ('COMPLETECALLER','COMPLETEAGENT') and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
Union
select 'CallAbandoned' TypeNya,COUNT(uniqueid) as jumlah from qstats.queue_stats_full where qname in ('2','3') and event='ABANDON' and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
) as a");*/
$result = $mysqli -> query("  select * from(
  select 'CallReceived' TypeNya,COUNT(*) as ValNya from cdr where dst in ('9000')  and DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE()
  Union
  select 'CallAnswered' TypeNya,COUNT(*) as ValNya from cdr where dst in ('9000') and disposition in ('ANSWERED') and DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE()
  Union
  select 'CallAbandoned' TypeNya,COUNT(*) as ValNya from cdr where dst in ('9000') and disposition in ('BUSY') and DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE()
  ) as a");

$data = [];
while ($row = $result->fetch_assoc()) {
   
    $data['DataDetail'][] = $row;
}

echo json_encode($data);
?>
