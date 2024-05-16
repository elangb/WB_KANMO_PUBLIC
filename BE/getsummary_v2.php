<?php
// Same as error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('GMT');

$mysqli = new mysqli("206.237.98.116","root","Uid35k32!Uid35k32!J4y4","qstats");

if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}


$sql = "SELECT asteriskcdrdb.ReportMonthly.labelreport as lastapp,DAY(calldate) AS hari,
  COUNT(jumlah) AS total_data,SUM(Seconds) as Seconds from(
select disposition as event,calldate,uniqueid as jumlah,billsec AS seconds from cdr where dst in ('9000')
union
select 'CONNECTA' as event,calldate,uniqueid as jumlah,billsec AS seconds from cdr where dst in ('9000')
union
select 'ENTERQUEUENEW',calldate,uniqueid as jumlah,billsec Seconds from cdr where dst in ('9000') and dstchannel=''
union
select 'EARLY',calldate,uniqueid as jumlah,billsec Seconds from cdr where disposition in ('NO ANSWER') and dst in ('9000') and duration between '0' and '9'
union
SELECT 'TOTALCALL',calldate,uniqueid as jumlah,billsec Seconds FROM cdr 
    WHERE  (duration-billsec) >=0 
   AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1)
   in ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016',
   'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029')
union
select 'EARLY',curdate(),1 as jumlah,0 Seconds
) as a left outer join asteriskcdrdb.ReportMonthly on asteriskcdrdb.ReportMonthly.event_id=a.event WHERE
  calldate !='' and labelreport !='' ".$add_query."
GROUP BY
  DAY(calldate),asteriskcdrdb.ReportMonthly.labelreport
ORDER BY
  asteriskcdrdb.ReportMonthly.urutan,DAY(calldate)";

  die($sql);
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


  //$datas['Service Level'][$i] = round((($datas['Call Answered Within'][$i] > 0)? ($datas['Call Answered Within'][$i] / ($datas['Total Call'][$i] - 
  //                                    $datas['Abnd. Ringing'][$i] - 
  //                                    $datas['Abnd. Transfer'][$i] -
  //                                    $datas['ivr terminated'][$i] - 
  //                                    $datas['early abandoned'][$i])) : 0), 2);
  // $datas['FTE Actual'][$i] = round((($datas['Call Answered'][$i] > 0)? ($datas['Call Answered'][$i] / ($datas['Total Call'][$i] - 
  //                                     $datas['Abnd. Ringing'][$i] - 
  //                                     $datas['Abnd. Transfer'][$i] -
  //                                     $datas['ivr terminated'][$i] - 
  //                                     $datas['early abandoned'][$i])) : 0), 2);

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