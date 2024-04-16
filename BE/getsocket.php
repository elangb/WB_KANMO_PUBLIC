<?php
// Input string containing information about the extension
$input_string = "10010 (Local/10010@from-queue/n from hint:10010@ext-local) (ringinuse enabled)[0m[0m[1;33;40m (in call)[0m ([1;32;40mIn use[0m) has taken 2 calls (last was 185 secs ago)";

// Check if the input string contains "(in call)"
if (strpos($input_string, "(in call)") !== false) {
    // Check if the input string contains "In use"
    if (strpos($input_string, "In use") !== false) {
        echo "Extension 10010 is in a call and in use.<br>";
    } else {
        echo "Extension 10010 is in a call but not in use.<br>";
    }
} else {
    echo "Extension 10010 is not in a call.<br>";
}



// Data
$data_extension = "10010,10011,10013";
$data_call = "10010 (Local/10010@from-queue/n from hint:10010@ext-local) (ringinuse enabled)[0m[0m[1;33;40m (in call)[0m ([1;32;40mIn use[0m) has taken 2 calls (last was 185 secs ago)";

// Extract extensions from data
$extensions = explode(',', $data_extension);

// Check if any extension is in a call
foreach ($extensions as $extension) {
    if (strpos($data_call, $extension) !== false && strpos($data_call, "(in call)") !== false) {
        echo "Extension $extension is in a call.<br>";
    }
}
?>
