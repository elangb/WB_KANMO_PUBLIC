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

function generate_url($row) {
    $date = $row['calldate'];
    list($tgl, $waktu) = explode(' ', $date);
    $path = str_replace("-", "/", trim($tgl));

    //return "https://sip.uidesk.id/stream.php?file=/var/spool/asterisk/monitor/".$path."/".$row['recordingfile'];
    return "https://sip.uidesk.id/stream.php?file=".$row['recordingfile'];
}

$add_query = "";
if(isset($_GET['search']) && !empty($_GET['search'])) $add_query .= "AND uniqueid LIKE '%".$_GET['search']."%' or dst LIKE '%".$_GET['search']."%'";
if(isset($_GET['start_date']) && !empty($_GET['start_date']) && isset($_GET['end_date']) && !empty($_GET['end_date'])) $add_query .= " AND (calldate BETWEEN '".$_GET['start_date']." 00:00:00' AND '".$_GET['end_date']." 23:59:59')";
// echo $add_query;
// die;

if($_GET['q']=="table"){
$three_days_ago = date("Y-m-d 00:00:00", strtotime("-3 days", time()));
$result = $mysqli -> query("select * from asteriskcdrdb.cdr 
where  calldate <> '' ".$add_query."  order by calldate ASC");
}elseif($_GET['q']=="summary"){
$three_days_ago = date("Y-m-d 00:00:00", strtotime("-3 days", time()));
$result = $mysqli -> query("select 'TOTAL CALL' as Keterangan,count(TotalCall) as TotalCall from(
select calldate as TotalCall from asteriskcdrdb.cdr where calldate <> '' ".$add_query." group by calldate
) as a

UNION
select 'CALL ANSWERED' as Keterangan,count(TotalCall) as TotalCall from(
select calldate as TotalCall from asteriskcdrdb.cdr where calldate <> '' ".$add_query." 
and disposition='ANSWERED' and dcontext='from-internal' group by calldate
) as a
UNION
select 'ABANDONED' as Keterangan,count(TotalCall) as TotalCall from(
select calldate as TotalCall from asteriskcdrdb.cdr where calldate <> '' ".$add_query."
and lastapp='BackGround' group by calldate
) as a
UNION
select 'IVR Terminated' as Keterangan,count(TotalCall) as TotalCall from(
select calldate as TotalCall from asteriskcdrdb.cdr where calldate <> '' ".$add_query."
and dcontext='ext-queues' and disposition='CONGESTION' group by calldate
) as a
UNION
select '% Within Service Level' as Keterangan,count(TotalCall) as TotalCall from(
select calldate as TotalCall from asteriskcdrdb.cdr where calldate <> '' ".$add_query."
and disposition='ANSWERED' and dcontext='from-internal' group by calldate
) as a");
}
$data = [];
while ($row = $result->fetch_assoc()) {
    $row['recordingfile_url'] = "https://sip.uidesk.id/stream.php?file=/var/spool/asterisk/monitor/".str_replace("-", "/", trim($row['Tgl']))."/".$row['recordingfile'];
    $data[] = $row;
}

echo json_encode($data);
?>