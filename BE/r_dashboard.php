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


$add_query = "";

$three_days_ago = date("Y-m-d 00:00:00", strtotime("-3 days", time()));

/*$result = $mysqli -> query("select * from(
select 'CallReceived' TypeNya,COUNT(uniqueid) as jumlah from qstats.queue_stats_full where qname in ('2','3') and event='CONNECT' and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
Union
select 'CallAnswered' TypeNya,COUNT(*) as ValNya from qstats.queue_stats_mv where (queue='60012' or queue='60011') and event in ('COMPLETECALLER','COMPLETEAGENT') and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
Union
select 'CallAbandoned' TypeNya,COUNT(uniqueid) as jumlah from qstats.queue_stats_full where qname in ('2','3') and event='ABANDON' and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
) as a");*/

$sql= "SELECT src,dst,lastapp,substring(channel,1,locate(\"-\",channel,1)-1) AS chan1, ";
$sql.="substring(dstchannel,1,locate(\"-\",dstchannel,1)-1) AS chan2, ";
$sql.="billsec, calldate,j1.dial,j2.dial,if(j1.dial is not null and j2.dial is null,'outbound','') as outbound, ";
$sql.="if(j1.dial is null and j2.dial is not null,'inbound','') AS inbound, ";
$sql.="if(j1.dial is not null and j2.dial is not null,'internal','') as internal ";
$sql.="FROM asteriskcdrdb.cdr LEFT JOIN asterisk.devices as j2 on substring(dstchannel,1,locate(\"-\",dstchannel,1)-1) = j2.dial ";
$sql.="LEFT JOIN asterisk.devices as j1 on substring(channel,1,locate(\"-\",channel,1)-1) = j1.dial WHERE DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE() AND billsec>0 AND disposition='ANSWERED' ";
$sql.="HAVING (outbound<>'' OR inbound<>'' OR internal<>'') AND chan2<>'' ORDER BY calldate DESC";
$result = $mysqli -> query($sql);

$data = [];
$inbound   = 0;
$outbound  = 0;
$internal  = 0;
$totaltime = 0;
$totalinboundtime  = 0;
$totaloutboundtime = 0;
$totalinternaltime = 0;
$totalcall = 0;
$avgtime   = 0;
$callsfrom = Array();
class DataDetail {
    public $Total_Calls;
    public $Total_Inbound_Calls;
    public $Total_Outbound_Calls;
    public $Total_Internal_Calls;
    public $Total_Minutes;
    public $Total_Inbound_Minutes;
    public $Total_Outbound_Minutes;
    public $Total_Internal_Minutes;
    public $Average_Inbound_Call_Duration;
    public $Average_Outbound_Call_Duration;
    public $Average_Internal_Call_Duration;
    // Add more properties as needed
}
while ($row = $result->fetch_assoc()) {
   
    //$data['DataDetail'][] = $row;
//}
$totalcall++;
        if($row['inbound']<>'') {
            $inbound++;
            $totalinboundtime+=$row['billsec'];
        } else if($row['outbound']<>'') {
            $totaloutboundtime+=$row['billsec'];
            $outbound++;
        } else if($row['internal']<>'') {
            $totalinternaltime+=$row['billsec'];
            $internal++;
        }
        if($row['internal']=='') {
            if(!isset($callsfrom[$row['src']])) {
                $callsfrom[$row['src']]=1;
            } else {
                $callsfrom[$row['src']]++;
            }
        }
    }
    $totalinboundtime = round($totalinboundtime/60,0);
    $totaloutboundtime = round($totaloutboundtime/60,0);
    $totalinternaltime = round($totalinternaltime/60,0);
    $totaltime = $totalinboundtime + $totaloutboundtime + $totalinternaltime;

    if($inbound>0) { 
        $avgtimein  = round($totalinboundtime / $inbound,2);
    } else {
        $avgtimein = 0;
    }

    if($outbound>0) { 
        $avgtimeout = round($totaloutboundtime / $outbound,2);
    } else {
        $avgtimeout = 0;
    }

    if($internal>0) { 
        $avgtimeinternal = round($totalinternaltime / $internal,2);
    } else {
        $avgtimeinternal = 0;
    }
    $object1 = new DataDetail();
    $object1->Total_Calls = $totalcall;
    $object1->Total_Inbound_Calls = $inbound;
    $object1->Total_Outbound_Calls = $outbound;
    $object1->Total_Internal_Calls = $internal;
    $object1->Total_Minutes = $totaltime;
    $object1->Total_Inbound_Minutes = $totalinboundtime;
    $object1->Total_Outbound_Minutes = $totaloutboundtime;
    $object1->Total_Internal_Minutes = $totalinternaltime;
    $object1->Average_Inbound_Call_Duration = $avgtimein;
    $object1->Average_Outbound_Call_Duration = $avgtimeout;
    $object1->Average_Internal_Call_Duration = $avgtimeinternal;
    
    
    
    // Create the array mapping
    $data['DataDetail'] = array($object1);
    
//echo json_encode($data);
// Create the array mapping
$data['DataDetail'] = array($object1);
echo json_encode($data, JSON_PRETTY_PRINT);    
?>

