<table class='table table-bordered' width="50%">
    <thead style='background: lightgray;'>
        <tr>
            <th><center>Hour</center></th>
			<th><center>Call Count</center></th>
			<th><center>SL%</center></th>
			<th><center>Agent</center></th>
			<th><center>Duration</center></th>
		</tr>
	</thead>
	<tbody>
<?php
include("getagents-lib.php");
$conn = new mysqli("sip.uidesk.id","root","Zimam@030622!!","asteriskcdrdb");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$start_date = $_GET['start_date'] ?? date('Y-m-d');
// Query untuk memilih data dari tabel
$sql = "SELECT 
    CONCAT(LPAD(hour_value, 2, '0'), ':00') AS Hour,
    COUNT(asteriskcdrdb.cdr.calldate) AS Total, COALESCE(SEC_TO_TIME(SUM(billsec)),0) AS seconds,disposition
FROM 
    qstats.hour_helper
LEFT JOIN 
    asteriskcdrdb.cdr ON HOUR(asteriskcdrdb.cdr.calldate) = hour_value
AND 
    DATE(asteriskcdrdb.cdr.calldate) = '".$start_date."'
AND
( substring(dstchannel,1,locate('-',dstchannel,1)-1) IN ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016',
   'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029') OR substring(channel,1,locate('-',channel,1)-1) IN ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016',
   'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029')) 
GROUP BY 
    hour_value,disposition
ORDER BY 
    hour_value;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Fetch the result set as an associative array
   
   $datas = [];
    while($data = $result->fetch_assoc()) {
		
		if (in_array($data['disposition'], ['ANSWERED', 'BUSY', 'NO ANSWER'])) {
			$datas[$data['Hour']][$data['disposition']] = [
				'Total' => $data['Total'] ?? 0,
				'seconds' => $data['seconds'] ?? 0
			];
		}
        //$query_fetch[] = $row;
		//echo "<tr><td>" . $row["Hour"]. "</td><td>" . $row["Total"]. "</td><td>" . $row["Hour"]. "</td><td>" . $row["Total"]. "</td><td>" . $row["seconds"]. "</td></tr>";
    }
	
	$data_agents = getAgentLogin(($_GET['start_date'] ?? date('Y-m-d')), ($_GET['end_date'] ?? date('Y-m-d')));
	// var_dump($data_agents['00']);
	//die();
	$new_datas = [];
	for ($i=0; $i < 24; $i++) { 
		$h = str_pad($i, 2, '0', STR_PAD_LEFT).":00";
		$h2 = str_pad($i, 2, '0', STR_PAD_LEFT);
		$seconds = $datas[$h]['ANSWERED']['seconds'] ?? "00:00:00";

		$answered = $datas[$h]['ANSWERED']['Total'] ?? 0;
		$busy = $datas[$h]['BUSY']['Total'] ?? 0;
		$no_answer = $datas[$h]['NO ANSWER']['Total'] ?? 0;

		$total = $answered + $busy + $no_answer;
		$sl = (round((($answered > 0)? ($answered / $total) : 0), 2) * 100)."%";
		
		$agent_count = $data_agents[$h2] ?? 0;
		
		$new_datas[] = [
			'Hour' => $h,
			'seconds' => $seconds,
			'Total' => $total,
			'SL' => $sl,
			'Agent' => $agent_count,
		];
		
		echo "<tr><td>" . $h. "</td><td>" . $total. "</td><td>" . $sl. "</td><td>".$agent_count."</td><td>" . $seconds. "</td></tr>";
    
	}

    // Free result set
    
    $conn->close();
} else {
    echo "Error in query: " . $conn->error;
}

?>
		
	</tbody>
</table>
