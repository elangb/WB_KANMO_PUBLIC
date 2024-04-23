<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Roatex Report Summary Monthly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                Roatex Report Summary Monthly
            </a>
        </div>
    </nav>
    <div class="card card-body">
            <form action="" method="get">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Start</label>
                            <input type="date" name="start_date" value="<?= $_GET['start_date'] ?? "" ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">End</label>
                            <input type="date" name="end_date" value="<?= $_GET['end_date'] ?? "" ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="input-group">
                   
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                </div>
            </form>
        </div>
<?php
$add_query = "";

if(isset($_GET['start_date']) && !empty($_GET['start_date']) && isset($_GET['end_date']) && !empty($_GET['end_date'])) $add_query .= " AND (datetime BETWEEN '".$_GET['start_date']."' AND '".$_GET['end_date']." 23:59:59')";
// echo $add_query;
// die;
// Your data
/*
$jsonData='[
	{
		"disposition" : "ANSWERED",
		"hari" : 5,
		"total_data" : 9
	},
	{
		"disposition" : "BUSY",
		"hari" : 5,
		"total_data" : 8
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 5,
		"total_data" : 1
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 6,
		"total_data" : 20
	},
	{
		"disposition" : "BUSY",
		"hari" : 6,
		"total_data" : 3
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 6,
		"total_data" : 20
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 7,
		"total_data" : 14
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 7,
		"total_data" : 6
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 11,
		"total_data" : 44
	},
	{
		"disposition" : "BUSY",
		"hari" : 11,
		"total_data" : 1
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 11,
		"total_data" : 14
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 12,
		"total_data" : 165
	},
	{
		"disposition" : "BUSY",
		"hari" : 12,
		"total_data" : 39
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 12,
		"total_data" : 606
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 12,
		"total_data" : 87
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 13,
		"total_data" : 72
	},
	{
		"disposition" : "BUSY",
		"hari" : 13,
		"total_data" : 5
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 13,
		"total_data" : 260
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 13,
		"total_data" : 19
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 14,
		"total_data" : 38
	},
	{
		"disposition" : "BUSY",
		"hari" : 14,
		"total_data" : 84
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 14,
		"total_data" : 576
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 14,
		"total_data" : 9
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 15,
		"total_data" : 24
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 15,
		"total_data" : 16
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 17,
		"total_data" : 11
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 17,
		"total_data" : 18
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 17,
		"total_data" : 2
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 18,
		"total_data" : 25
	},
	{
		"disposition" : "BUSY",
		"hari" : 18,
		"total_data" : 4
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 18,
		"total_data" : 24
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 19,
		"total_data" : 42
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 19,
		"total_data" : 148
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 19,
		"total_data" : 12
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 20,
		"total_data" : 37
	},
	{
		"disposition" : "BUSY",
		"hari" : 20,
		"total_data" : 8
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 20,
		"total_data" : 122
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 20,
		"total_data" : 14
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 21,
		"total_data" : 12
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 21,
		"total_data" : 10
	},
	{
		"disposition" : "ANSWERED",
		"hari" : 22,
		"total_data" : 27
	},
	{
		"disposition" : "CONGESTION",
		"hari" : 22,
		"total_data" : 10
	},
	{
		"disposition" : "NO ANSWER",
		"hari" : 22,
		"total_data" : 1
	}
]';
*/
// Connection to the database
$mysqli = new mysqli("sip.uidesk.id","root","Zimam@030622!!","asteriskcdrdb");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Your SQL query and (dst <> '6003' and dst <> '6004') (dst !='6003' and dst !='6004')
//where (YEAR(calldate) > '2022' and recordingfile != '') AND (dst !='6003' and dst !='6004')  AND disposition != 'CONGESTION'
/*$sql = "SELECT
          dcontext as lastapp,DAY(calldate) AS hari,
          COUNT(*) AS total_data
        FROM
          asteriskcdrdb.cdr
        WHERE
          calldate !='' AND (dcontext<>'To-Kanmo' AND dcontext<>'sub-pincheck' AND dcontext<>'ext-group' AND dcontext<>'app-blackhole') ".$add_query."
        GROUP BY
          DAY(calldate),dcontext
        ORDER BY
          DAY(calldate);";*/
/*$sql = "SELECT
	  event as lastapp,DAY(datetime) AS hari,
	  COUNT(*) AS total_data
	FROM
	  qstats.queue_stats_mv
	WHERE
	  datetime !='' AND (queue='60012' or queue='60011') ".$add_query."
	GROUP BY
	  DAY(datetime),event
	ORDER BY
	  DAY(datetime);";*/
/*last$sql = "SELECT
	  event as lastapp,DAY(datetime) AS hari,
	  COUNT(*) AS total_data from(
select event,datetime from qstats.queue_stats_mv where (queue='60012' or queue='60011') 
union
select event,datetime from v_RawQstats where (event<>'DID') 
) as a WHERE
	  datetime !='' ".$add_query."
	GROUP BY
	  DAY(datetime),event
	ORDER BY
	  DAY(datetime);";*/
	  
//CDR data disposition in ('BUSY','NO ANSWER','ANSWERED') and
//union
//select event,datetime,1 as jumlah from asteriskcdrdb.v_RawQstats

$sql = " SELECT
  qstats.reportmonthly.labelreport as lastapp,DAY(datetime) AS hari,
  COUNT(jumlah) AS total_data,SUM(Seconds) as Seconds from(
select event,datetime,real_uniqueid as jumlah,0 Seconds from qstats.queue_stats_mv where (queue='60012' or queue='60011') 
union
select disposition as event,calldate,uniqueid as jumlah,0 AS seconds from asteriskcdrdb.cdr where dst in ('60012','60011')
union
select 'CONNECTA' as event,calldate,uniqueid as jumlah,billsec AS seconds from asteriskcdrdb.cdr where dst in ('60012','60011')
union
select 'CALLWITHIN',datetime,real_uniqueid as jumlah,0 Seconds from qstats.queue_stats_mv where (queue='60012' or queue='60011') and event in ('COMPLETECALLER','COMPLETEAGENT') and ringtime <=20
union
select 'ENTERQUEUENEW',calldate,uniqueid as jumlah,0 Seconds from asteriskcdrdb.cdr where dst in ('60012','60011') and dstchannel=''
union
select a.event,a.datetime,a.uniqueid as jumlah,(SELECT g.duration FROM asteriskcdrdb.cdr g WHERE g.uniqueid = a.uniqueid and g.disposition='ANSWERED' ORDER BY uniqueid DESC LIMIT 1) AS Seconds from qstats.queue_stats_full a where a.qname in ('2','3')
union
select 'EARLY',calldate,uniqueid as jumlah,0 Seconds from asteriskcdrdb.cdr where disposition in ('NO ANSWER') and dst in ('60012','60011') and duration between '0' and '9'
union
SELECT 'TOTALCALL',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr 
    WHERE  (duration-billsec) >=0 
   AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1)
   in ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016',
   'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029')
union
select 'EARLY',curdate(),1 as jumlah,0 Seconds
) as a left outer join qstats.reportmonthly on qstats.reportmonthly.event_id=a.event WHERE
  datetime !='' and labelreport !='' ".$add_query."
GROUP BY
  DAY(datetime),qstats.reportmonthly.labelreport
ORDER BY
  qstats.reportmonthly.urutan,DAY(datetime);";
  
$result = $mysqli->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the result set as an associative array
    $query_fetch = [];
    while ($row = $result->fetch_assoc()) {
        $query_fetch[] = $row;
    }
	
	//echo json_encode($query_fetch);
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
    $datas[$data['lastapp']][$data['hari']] = $data['total_data'] ?? 0;
    $seconds[$data['lastapp']][$data['hari']] = $data['Seconds'] ?? 0;
}

?>
<div class='mt-3'></div>
<table class='table table-bordered'>
    <thead style='background: lightgray;'>
        <tr>
            <th><center>INBOUND CUSTOMER</center></th>
            <?php for ($i=1; $i <= 31; $i++) { 
                echo "<th onclick='openDetail(".$i.")'>".$i."</th>";

                $datas['Call Answered'][$i] = $datas['Call Answered'][$i] ?? 0;
				$datas['Call Answered Within'][$i] = $datas['Call Answered Within'][$i] ?? 0;
                $datas['Total Call'][$i] = $datas['Total Call'][$i] ?? 0;
                $datas['Abnd. Ringing'][$i] = $datas['Abnd. Ringing'][$i] ?? 0;
                $datas['Abnd. Transfer'][$i] = $datas['Abnd. Transfer'][$i] ?? 0;
                $datas['Abnd. Queue'][$i] = $datas['Abnd. Queue'][$i] ?? 0;
                $datas['ivr terminated'][$i] = $datas['ivr terminated'][$i] ?? 0;
                $datas['early abandoned'][$i] = $datas['early abandoned'][$i] ?? 0;

                $datas['SCR'][$i] = round((($datas['Call Answered'][$i] > 0)? ($datas['Call Answered'][$i] / $datas['Total Call'][$i]) : 0), 2)*100;

                $datas['Service Level'][$i] = round((($datas['Call Answered Within'][$i] > 0)? ($datas['Call Answered Within'][$i] / $datas['Total Call'][$i]) : 0), 2)*100;

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
				$aht = ($datas['Call Answered'][$i] > 0)? round(($seconds['Call Answered'][$i] ?? 0) / $datas['Call Answered'][$i], 2) : 0;
                $datas['Average Handling Time (AHT)'][$i] = $aht;
                $datas['Idle rate %'][$i] = 0;
                $datas['CSAT'][$i] = 0;
            }
            
			//echo json_encode($datas);
			//die();
            // print_r($datas);
            
            ?>
        </tr>
    </thead>
    <tbody>
        <?php 
            foreach ($datas as $dispotition => $dates) {
				echo "<tr><td style='background: blue;color:white;'><center>{$dispotition}<center></td>";

                for ($i=1; $i <= 31; $i++) { 
                    if(in_array($dispotition, ["Service Level", "SCR"])) {
                        echo "<td>".($dates[$i])."%</td>";
                    } else {
                        echo "<td>".($dates[$i])."</td>";
                    }
                }
                echo "</tr>";
            }
        ?>
    </tbody>
</table>

<div id="detail"></div>

</body>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
	<script>
		function addZero(num) {
			let n = num;
			if(num < 10) {
				n = "0"+num;
			}
			
			return n;
		}
		function openDetail(day) {
			$("#detail").html("");
			let n = addZero(day);
			let queryString = window.location.search;
			let urlParams = new URLSearchParams(queryString);
			let date;
			if(urlParams.has('start_date')) {
				date = new Date(urlParams.get('start_date'));
			} else {
				date = new Date();
			}
			
			let th = date.getFullYear(),
				bln = date.getMonth();
			//let start_date, end_date;
				date_selected = addZero(th)+"-"+addZero(bln+1)+"-"+addZero(day)
				
			
			$("#detail").load( "reportsummarydetail.php?start_date="+date_selected+"&end_date="+date_selected );
		}
	</script>
</html>