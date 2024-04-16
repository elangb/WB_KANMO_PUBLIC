<?php
$mysqli = new mysqli("sip.uidesk.id","root","Zimam@030622!!","asteriskcdrdb");
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
select 'CallReceived' TypeNya,COUNT(*) as ValNya from asteriskcdrdb.cdr where DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE()
Union
select 'CallAnswered' TypeNya,COUNT(*) as ValNya from asteriskcdrdb.cdr where DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()  and disposition='ANSWERED' 
Union
select 'CallAbandoned' TypeNya,COUNT(*) as ValNya from asteriskcdrdb.cdr where DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE()  and (disposition='NO ANSWER' or disposition='BUSY') 
) as a");*/
$result = $mysqli -> query("select * from(
select 'CallReceived' TypeNya,COUNT(uniqueid) as jumlah from qstats.queue_stats_full where qname in ('2','3') and event='CONNECT' and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
Union
select 'CallAnswered' TypeNya,COUNT(*) as ValNya from qstats.queue_stats_mv where (queue='60012' or queue='60011') and event in ('COMPLETECALLER','COMPLETEAGENT') and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
Union
select 'CallAbandoned' TypeNya,COUNT(uniqueid) as jumlah from qstats.queue_stats_full where qname in ('2','3') and event='ABANDON' and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
) as a");

$data = [];
while ($row = $result->fetch_assoc()) {
   
    $data[] = $row;
}

echo json_encode($data);
?>
