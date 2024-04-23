<?php
 include('Net/SSH2.php');
 ini_set('display_errors', 0);
 ini_set('display_startup_errors', 0);
   //error_reporting(E_ALL);
   $key ="Uid35k32!J4y4J4y4";
   $ssh1 = new Net_SSH2('206.237.98.116', 3389);   // Domain or IP
   if (!$ssh1->login('root', $key))   exit('Login Failed'); 
 $numbers1 = array(10010);
 $ready=0;
 $notready=0;
 $get100111result=0;
 $myObj = new stdClass();
 $outputArray = array();
 
 
$get100111= $ssh1->exec('sudo asterisk -x "queue show"');
     

// Split data into lines
$lines = explode("\n", $get100111);

$result = array();

foreach ($lines as $line) {
    // Extracting necessary information from each line
    if (preg_match('/^(\d+) has (\d+) calls \(max unlimited\) in \'(\w+)\' strategy \((\d+)s holdtime, (\d+)s talktime\), W:(\d+), C:(\d+), A:(\d+), SL:(\d+\.\d+)%/', $line, $matches)) {
        $result[$matches[1]] = array(
            'calls' => intval($matches[2]),
            'strategy' => $matches[3],
            'holdtime' => intval($matches[4]),
            'talktime' => intval($matches[5]),
            'W' => intval($matches[6]),
            'C' => intval($matches[7]),
            'A' => intval($matches[8]),
            'SL' => floatval($matches[9]),
            'members' => array()
        );
    } elseif (preg_match('/^\s+([^\(]+)\s+\(Local\/(\d+)@from-queue\/n/', $line, $matches)) {
        // Extracting member information\
        /*if (preg_match('/\(in call\)/', $line, $matchesX)) {
           $call='in call';
        } else {
            $call='idle';
        }*/
        if (strpos($line, 'Not in use') == true) {
            $call='Ready';
         } else if (strpos($line, 'In use') == true) {
             $call='InCall';
          }else if (strpos($line, 'Ringing') == true) {
             $call='Ringing';
          }else if (strpos($line, 'Unavailable') == true) {
             $call='Unavailable';
          }else {
             $call='idle';
         }
        $pattern = '/has taken (\d+) calls \(last was (\d+) secs ago\)/';

        // Using preg_match to extract the desired information
        if (preg_match($pattern, $line, $matchesY)) {
            $callsTaken = $matchesY[1];
            $lastCallTime = $matchesY[2];
            //echo "Calls taken: $callsTaken, Last call was $lastCallTime seconds ago.";
        } else {
            //echo "Information not found in the string.";
        }
        $member = array(
            'name' => trim($matches[1]),
            'local' => intval($matches[2]),
            'statuscall' => $call,
            'callstaken' => $callsTaken,
            'lastcalltime' => $lastCallTime
        );
        // Adding member information to the result
        $result[end(array_keys($result))]['members'][] = $member;
    }
}

// Convert result to JSON
$json_result = json_encode($result, JSON_PRETTY_PRINT);

// menampilkan data JSON echo $json_result;
// Step 1: Decode JSON data into PHP associative array
$data = json_decode($json_result, true);

// Step 2: Extract and combine all "members" arrays
$allMembers = [];
foreach ($data as $item) {
    $allMembers = array_merge($allMembers, $item['members']);
}

// Step 3: Sort the combined array by "lastcalltime" in descending order
usort($allMembers, function($a, $b) {
    return $b['lastcalltime'] - $a['lastcalltime'];
});

// Output the sorted array
echo json_encode($allMembers, JSON_PRETTY_PRINT);
?>