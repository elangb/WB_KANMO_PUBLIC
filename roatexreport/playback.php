<?php

if (empty($_GET['form_uniqueid'])) die("Data is'nt found");

$mysqli = new mysqli("pbx.uidesk.id","uidesk","Uidesk123!","asteriskcdrdb");


// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

function generate_url($row) {
    $date = $row['calldate'];
    list($tgl, $waktu) = explode(' ', $date);
    $path = str_replace("-", "/", trim($tgl));

    return "https://pbx.uidesk.id/stream.php?file=/var/spool/asterisk/monitor/".$path."/".$row['recordingfile'];
}

function curlTranscript($audioURL) {
    $fileName = basename($audioURL);
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'http://202.157.187.152:5000/translate',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('audio_file'=> new CURLFILE($audioURL),'id' => 'RTR_'.time(),'audio_filename' => $fileName),
      CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer mt0dgHmL238QhvjpNXDyA83vA_Pxh33Y'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    
    return $response;
}

$result = $mysqli ->query("SELECT * FROM cdr where uniqueid='".$_GET['form_uniqueid']."'");
$arr_datas = $result->fetch_assoc();

if($arr_datas['has_transcript'] == 0) {
    $get_transcript = curlTranscript(generate_url($arr_datas));
    $mysqli->query("UPDATE cdr SET has_transcript=1, transcript='$get_transcript' WHERE uniqueid='$arr_datas[uniqueid]'");
} else {
    $get_transcript = $arr_datas['transcript'];
}
$transcript = json_decode($get_transcript, true);
// file_put_contents("transcript.json", $transcript);

?>

                            <audio controls>
                                <source src="<?= generate_url($arr_datas) ?>" type="audio/wav">
                                Your browser does not support the audio element.
                            </audio>
<!--<audio crossorigin preload="none" controls id="audio-player">-->
<!--    <source src="<?= generate_url($arr_datas) ?>" type="audio/wav">-->
<!--</audio>-->
<?php foreach($transcript['results'] as $key => $result) { ?>

    <div style="margin-top: 20px">
        <h3 style="margin-bottom: 10px;">Transcript - <?= $key+1 ?></h6>
        <span><?= $result['text']; ?></span>
    </div>
    
    <?php } ?>

<?php die(); ?>