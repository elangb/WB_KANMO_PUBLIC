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
 
 
 $get100111 = $ssh1->exec('sudo asterisk -x "queue show"');
 $get100112 = $ssh1->exec('sudo asterisk -x "queue show 60012"');
 
 // Split the command output into lines
 $lines = explode("\n", $get100111);
 $lines2 = explode("\n", $get100112);
 
 // Initialize result array
 $result = array();
 
 // Function to process lines and extract queue and member data
 function processQueueLines($lines, &$result) {
     $currentQueue = null;
 
     foreach ($lines as $line) {
         // Check for queue details
         if (preg_match('/^(\d+) has (\d+) calls \(max unlimited\) in \'(\w+)\' strategy \((\d+)s holdtime, (\d+)s talktime\), W:(\d+), C:(\d+), A:(\d+), SL:(\d+\.\d+)%/', $line, $matches)) {
             $queueId = $matches[1];
             $result[$queueId] = array(
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
             $currentQueue = $queueId; // Set current queue context
         } elseif ($currentQueue !== null && preg_match('/^\s+([^\(]+)\s+\(Local\/(\d+)@from-queue\/n/', $line, $matches)) {
             // Determine the status of the member
             if (strpos($line, 'Not in use') !== false) {
                 $call = 'Ready';
             } elseif (strpos($line, 'In use') !== false) {
                 $call = 'InCall';
             } elseif (strpos($line, 'Ringing') !== false) {
                 $call = 'Ringing';
             } elseif (strpos($line, 'Unavailable') !== false) {
                 $call = 'Unavailable';
             } else {
                 $call = 'Idle';
             }
 
             // Extract additional member info
             $callsTaken = $lastCallTime = 'N/A'; // Default values if not found
             if (preg_match('/has taken (\d+) calls \(last was (\d+) secs ago\)/', $line, $matchesY)) {
                 $callsTaken = $matchesY[1];
                 $lastCallTime = $matchesY[2];
             }
 
             $member = array(
                 'name' => trim($matches[1]),
                 'local' => intval($matches[2]),
                 'statuscall' => $call,
                 'callstaken' => $callsTaken,
                 'lastcalltime' => $lastCallTime
             );
 
             // Adding member information to the result
             $result[$currentQueue]['members'][] = $member;
         }
     }
 }
 
 // Process both sets of lines
 processQueueLines($lines, $result);
 processQueueLines($lines2, $result);
 
 // Extract and combine all "members" arrays
 $allMembers = [];

 foreach ($result as $queueData) {
     if (isset($queueData['members'])) {
         $allMembers = array_merge($allMembers, $queueData['members']);
     }
 }

 // Sort the combined array by "lastcalltime" in descending order
 usort($allMembers, function($a, $b) {
     return ($b['lastcalltime'] === 'N/A' ? -1 : (int)$b['lastcalltime']) - ($a['lastcalltime'] === 'N/A' ? -1 : (int)$a['lastcalltime']);
 });
 
 // Output the sorted array as JSON
 header('Content-Type: application/json');

 echo json_encode($allMembers, JSON_PRETTY_PRINT);
?>