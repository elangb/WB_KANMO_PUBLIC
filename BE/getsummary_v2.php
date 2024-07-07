<?php
// Same as error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('GMT');



$mysqli = new mysqli("202.43.173.61","matthew","supersecretpassword","asteriskcdrdb");

if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}


$sql = "SELECT qstats.reportmonthly.labelreport as lastapp,DAY(datetime) AS hari
, COUNT(jumlah) AS total_data,SUM(Seconds) as Seconds from( select event,datetime,real_uniqueid as jumlah,0 Seconds from qstats.queue_stats_mv where (queue='60011' or queue='60012')
 union 
select disposition as event,calldate,uniqueid as jumlah,0 AS seconds from asteriskcdrdb.cdr where disposition in ('NO ANSWER') AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016', 'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029')
union select 'CONNECTA' as event,calldate,uniqueid as jumlah,billsec AS seconds from asteriskcdrdb.cdr where dst in ('60012','60011') 
union 
select 'NEWANSWERED',a.calldate,a.uniqueid,a.billsec as  
Seconds 
from( select recordingfile,SUM(duration) as Ringtime,calldate,uniqueid,billsec 
from( SELECT substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) 
AS chan1,asteriskcdrdb.cdr.* FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 HAVING 
chan1 in ('SIP/102030','SIP/102031','SIP/102032','SIP/102033','SIP/102034','SIP/102035','SIP/102036', 'SIP/102037','SIP/102038') ) as a where a.disposition='ANSWERED' group by recordingfile ) 
as a 
where Ringtime>0 
union 
SELECT 'OUTBOUND',a.calldate,a.uniqueid,0 Seconds from( select substring(channel,1,locate('-',channel,1)-1) AS chan1, billsec, calldate,uniqueid, (time_to_sec(calldate)-(hour(calldate)*3600)+billsec)-3600 AS minute, hour(calldate) AS hour,date_format(calldate,'%Y%m%d') AS fulldate FROM asteriskcdrdb.cdr WHERE substring(channel,1,locate('-',channel,1)-1)<>'' AND (duration-billsec) >=0 
HAVING chan1 IN ('SIP/102030','SIP/102031','SIP/102032','SIP/102033','SIP/102034','SIP/102035','SIP/102036', 'SIP/102037','SIP/102038') ) as a 
union 
select 'CALLWITHIN',a.calldate,a.uniqueid,a.billsec as  
Seconds 
from( select recordingfile,SUM(duration) as Ringtime,calldate,uniqueid,billsec 
from( SELECT substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) 
AS chan1,asteriskcdrdb.cdr.* FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 HAVING 
chan1 in ('SIP/102030','SIP/102031','SIP/102032','SIP/102033','SIP/102034','SIP/102035','SIP/102036', 'SIP/102037','SIP/102038') ) as a where a.disposition='ANSWERED' group by recordingfile ) 
as a 
where Ringtime>0
union 
select 'ENTERQUEUENEWa',calldate,uniqueid as jumlah,0 Seconds from asteriskcdrdb.cdr where dst in ('60012','60011') and dstchannel='' 
union 
select a.event,a.datetime,a.uniqueid as jumlah,(SELECT g.duration FROM asteriskcdrdb.cdr g WHERE g.uniqueid = a.uniqueid and g.disposition='ANSWERED' ORDER BY uniqueid DESC LIMIT 1) AS Seconds from qstats.queue_stats_full a where a.qname in ('2','3') and a.queue in ('60012','60011') 
union 
select 'EARLYa',calldate,uniqueid as jumlah,0 Seconds from asteriskcdrdb.cdr where disposition in ('NO ANSWER') and dst in ('60012','60011') and duration between '0' and '9' 
union 
SELECT 'TOTALCALL',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr 
WHERE (duration-billsec) >=0 AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/102030','SIP/102031','SIP/102032','SIP/102033','SIP/102034','SIP/102035','SIP/102036', 'SIP/102037','SIP/102038') 
union 
select 'EARLY',curdate(),0 as jumlah,0 Seconds ) as a left outer join qstats.reportmonthly on qstats.reportmonthly.event_id=a.event WHERE datetime !='' 
and labelreport !='' 
AND DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE() 
GROUP BY DAY(datetime),qstats.reportmonthly.labelreport 
ORDER BY qstats.reportmonthly.urutan,DAY(datetime)";

 
$result = $mysqli->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the result set as an associative array
    
    while ($row = $result->fetch_assoc()) {
        $query_fetch[] = $row;
    }
	
	
	//die();

    // Free result set
    $result->free_result();

    // Close connection
    $mysqli->close();
} else {
    echo "Error in query: " . $mysqli->error;
}

$datas = [];
$seconds = [];

foreach ($query_fetch as $key => $data) {
    if (isset($data['total_data'])) {
    $datas[$data['lastapp']][$data['hari']] = $data['total_data'];
	} else {
		$datas[$data['lastapp']][$data['hari']] = 0;
	}
	if (isset($data['Seconds'])) {
		$seconds[$data['lastapp']][$data['hari']] = $data['Seconds'];
	} else {
		$seconds[$data['lastapp']][$data['hari']] = 0;
	}
}

$data = [];
for ($i=date('j'); $i <= date('j'); $i++) { 
 
  $datas['Call Answered'][$i] = isset($datas['Call Answered'][$i]) ? $datas['Call Answered'][$i] : 0;
$datas['Call Answered Within'][$i] = isset($datas['Call Answered Within'][$i]) ? $datas['Call Answered Within'][$i] : 0;
$datas['Total Call'][$i] = isset($datas['Total Call'][$i]) ? $datas['Total Call'][$i] : 0;
$datas['Abnd. Ringing'][$i] = isset($datas['Abnd. Ringing'][$i]) ? $datas['Abnd. Ringing'][$i] : 0;
$datas['Abnd. Transfer'][$i] = isset($datas['Abnd. Transfer'][$i]) ? $datas['Abnd. Transfer'][$i] : 0;
$datas['Abnd. Queue'][$i] = isset($datas['Abnd. Queue'][$i]) ? $datas['Abnd. Queue'][$i] : 0;
$datas['ivr terminated'][$i] = isset($datas['ivr terminated'][$i]) ? $datas['ivr terminated'][$i] : 0;
$datas['early abandoned'][$i] = isset($datas['early abandoned'][$i]) ? $datas['early abandoned'][$i] : 0;

$datas['SCR'][$i] = round((($datas['Call Answered'][$i] > 0) ? ($datas['Call Answered'][$i] / $datas['Total Call'][$i]) : 0), 2) * 100;
$datas['Service Level'][$i] = round((($datas['Call Answered'][$i] > 0) ? ($datas['Call Answered'][$i] / $datas['Total Call'][$i]) : 0), 2) * 100;


$datas['FTE Actual'][$i] = 0;
	if ($datas['Call Answered'][$i] > 0) {
		if (isset($seconds['Call Answered'][$i]) && $datas['Call Answered'][$i] != 0) {
		$aht = round($seconds['Call Answered'][$i] / $datas['Call Answered'][$i], 2);
	} else {
		$aht = 0;
	}
	} else {
		$aht = 0;
	}
	
	
  $datas['Average Handling Time (AHT)'][$i] = $aht;
  $datas['Idle rate %'][$i] = 0;
  $datas['CSAT'][$i] = 0;

  
}
$data['DataDetail'][] = $datas;
echo json_encode($data);

?>