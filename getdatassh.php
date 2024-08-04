<?php

$data = "default has 0 calls (max unlimited) in 'ringall' strategy (0s holdtime, 0s talktime), W:0, C:0, A:0, SL:0.0%, SL2:0.0% within 0s
   No Members
   No Callers

60012 has 0 calls (max unlimited) in 'random' strategy (2s holdtime, 173s talktime), W:0, C:658, A:28, SL:99.8%, SL2:99.6% within 60s
   Members:
      Demmy Fitra (Local/10011@from-queue/n from hint:10011@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40m (in call)[0m) has taken 8 calls (last was 14264 secs ago)
      Rino Andriansyah (Local/10020@from-queue/n from hint:10020@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 2 calls (last was 17481 secs ago)
      Rizky Alfiandi (Local/10021@from-queue/n from hint:10021@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 3 calls (last was 105268 secs ago)
      Nimah Rifda Yusuf (Local/10022@from-queue/n from hint:10022@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 6 calls (last was 16889 secs ago)
      Devira Afandi (Local/10024@from-queue/n from hint:10024@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40m (in call)[0m) has taken 4 calls (last was 104763 secs ago)
      Mega Rahmawati (Local/10027@from-queue/n from hint:10027@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 4 calls (last was 17190 secs ago)
   No Callers

60013 has 0 calls (max unlimited) in 'random' strategy (1s holdtime, 67s talktime), W:0, C:2, A:17, SL:100.0%, SL2:100.0% within 60s
   Members:
      TEST (Local/1009@from-queue/n from hint:1009@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken no calls yet
   No Callers

60011 has 0 calls (max unlimited) in 'rrordered' strategy (3s holdtime, 316s talktime), W:0, C:444, A:23, SL:100.0%, SL2:99.4% within 60s
   Members:
      Demmy Fitra (Local/10011@from-queue/n from hint:10011@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 1 calls (last was 17279 secs ago)
      Alvin Juliardy Santofia (Local/10014@from-queue/n from hint:10014@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 3 calls (last was 17701 secs ago)
      Jauhar Andre (Local/10017@from-queue/n from hint:10017@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 1 calls (last was 106862 secs ago)
      Antony Tambunan (Local/10019@from-queue/n from hint:10019@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 3 calls (last was 15994 secs ago)
      Ryanti Rahmawati (Local/10025@from-queue/n from hint:10025@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 3 calls (last was 17251 secs ago)
      Jayanti Setiawati (Local/10026@from-queue/n from hint:10026@ext-local) (ringinuse enabled)[0m[0m[0m ([1;31;40mUnavailable[0m) has taken 1 calls (last was 104975 secs ago)
   No Callers";

// Split data into lines
$lines = explode("\n", $data);

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
        if (preg_match('/\(in call\)/', $line, $matchesX)) {
           $call='in call';
        } else {
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

echo $json_result;

?>
