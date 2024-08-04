<?php
$mysqli = new mysqli("202.43.173.61","root","Uid35k32!Uid35k32!J4y4","asteriskcdrdb");
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
$result = $mysqli -> query("select 'Outbound' as TypeNya,COUNT(*) as Jumlah from(
    SELECT substring(channel,1,locate('-',channel,1)-1) AS chan1
    FROM asteriskcdrdb.cdr WHERE  substring(channel,1,locate('-',channel,1)-1)<>'' 
    AND DATE(calldate)=CURDATE() AND (duration-billsec) >=0;");

$data = [];
while ($row = $result->fetch_assoc()) {
   
    $data['DataDetail'][] = $row;
}

echo json_encode($data);
?>
