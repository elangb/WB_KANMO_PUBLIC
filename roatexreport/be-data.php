<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
/*$hostname = "149.28.156.138";
$database = "mixradius_radDB";
$username = "103.79.131.74";
$password = "qcXRAlD7HBfD9B1Z";
*/
$mysqli = new mysqli("149.28.156.138","103.79.131.74","qcXRAlD7HBfD9B1Z","mixradius_radDB");
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
if(isset($_GET['start_date']) && !empty($_GET['start_date']) && isset($_GET['end_date']) && !empty($_GET['end_date'])) $add_query .= " AND (calldate BETWEEN '".$_GET['start_date']."' AND '".$_GET['end_date']." 23:59:59')";
// echo $add_query;
// die;

$three_days_ago = date("Y-m-d 00:00:00", strtotime("-3 days", time()));
$result = $mysqli -> query("SELECT a.id, a.member_id, a.type, a.servicetype, a.username, a.password, a.fullname, a.email, a.phonenumber, a.address, a.plan_name, a.renewed_on, a.expired_on, a.trx_invoice, a.trx_status, a.payment_type,b.latitude, b.longitude FROM tbl_customers a LEFT JOIN tbl_customers_map b ON b.customer_id = a.id");

$data = [];
while ($row = $result->fetch_assoc()) {
    //$row['recordingfile_url'] = "https://sip.uidesk.id/stream.php?file=/var/spool/asterisk/monitor/".str_replace("-", "/", trim($row['Tgl']))."/".$row['recordingfile'];
    $data[] = $row;
}

echo json_encode($data);
?>