<?php

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

// Your SQL query and (dst <> '6003' and dst <> '6004')
$sql = "SELECT
          lastapp,DAY(calldate) AS hari,
          COUNT(*) AS total_data
        FROM
          asteriskcdrdb.cdr
        WHERE
          MONTH(calldate) = 12 AND YEAR(calldate) = 2023 
        GROUP BY
          DAY(calldate),lastapp
        ORDER BY
          DAY(calldate);";
$result = $mysqli->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch the result set as an associative array
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Free result set
    $result->free_result();

    // Close connection
    $mysqli->close();

    // Convert the array to JSON
    $jsonResult = json_encode($data);

    // Output the JSON string
    //echo $jsonResult;
} else {
    echo "Error in query: " . $mysqli->error;
}


$data = json_decode($jsonResult, true);

// Initialize an empty result array
$result = [];

// Loop through the data to group and pivot
foreach ($data as $entry) {
    $disposition = $entry["lastapp"];
    $hari = $entry["hari"];
    $totalData = $entry["total_data"];

    // Check if the disposition key exists in the result array
    if (!array_key_exists($disposition, $result)) {
        $result[$disposition] = [];
    }

    // Check if the hari key exists in the disposition array
    if (!array_key_exists($hari, $result[$disposition])) {
        $result[$disposition][$hari] = 0;
    }

    // Sum the total_data for each hari on a given disposition
    $result[$disposition][$hari] += $totalData;
}

// Display the result in an HTML table
echo "<table border='1'>";
echo "<tr><th>Disposition</th><th>Tgl 1</th><th>Tgl 2</th><th>Tgl 3</th><th>Tgl 4</th><th>Tgl 5</th><th>Tgl 6</th><th>Tgl 7</th><th>Tgl 8</th><th>Tgl 9</th><th>Tgl 10</th><th>Tgl 11</th><th>Tgl 12</th><th>Tgl 13</th><th>Tgl 14</th>
<th>Tgl 15</th><th>Tgl 16</th><th>Tgl 17</th><th>Tgl 18</th><th>Tgl 19</th><th>Tgl 20</th><th>Tgl 21</th><th>Tgl 22</th><th>Tgl 23</th><th>Tgl 24</th><th>Tgl 25</th>
<th>Tgl 26</th><th>Tgl 27</th><th>Tgl 28</th><th>Tgl 29</th><th>Tgl 30</th><th>Tgl 31</th></tr>";


foreach ($result as $disposition => $haris) {
    echo "<tr>";
    echo "<td>{$disposition}</td>";
    echo "<td>{$haris['1']}</td>";
    echo "<td>{$haris['2']}</td>";
    echo "<td>{$haris['3']}</td>";
    echo "<td>{$haris['4']}</td>";
    echo "<td>{$haris['5']}</td>";
    echo "<td>{$haris['6']}</td>";
    echo "<td>{$haris['7']}</td>";
    echo "<td>{$haris['8']}</td>";
    echo "<td>{$haris['9']}</td>";
    echo "<td>{$haris['10']}</td>";
    echo "<td>{$haris['11']}</td>";
    echo "<td>{$haris['12']}</td>";
    echo "<td>{$haris['13']}</td>";
    echo "<td>{$haris['14']}</td>";
    echo "<td>{$haris['15']}</td>";
    echo "<td>{$haris['16']}</td>";
    echo "<td>{$haris['17']}</td>";
    echo "<td>{$haris['18']}</td>";
    echo "<td>{$haris['19']}</td>";
    echo "<td>{$haris['20']}</td>";
    echo "<td>{$haris['21']}</td>";
    echo "<td>{$haris['22']}</td>";
    echo "<td>{$haris['23']}</td>";
    echo "<td>{$haris['24']}</td>";
    echo "<td>{$haris['25']}</td>";
    echo "<td>{$haris['26']}</td>";
    echo "<td>{$haris['27']}</td>";
    echo "<td>{$haris['28']}</td>";
    echo "<td>{$haris['29']}</td>";
    echo "<td>{$haris['30']}</td>";
    echo "<td>{$haris['31']}</td>";
    
    echo "</tr>";
}

echo "</table>";

?>
