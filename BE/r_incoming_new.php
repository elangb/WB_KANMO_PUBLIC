<?php
ini_set("error_reporting", E_ALL);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('GMT');
$mysqli = new mysqli("206.237.98.116","root","Uid35k32!Uid35k32!J4y4","asteriskcdrdb");
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

$chanfield      = "dstchannel";
    $otherchanfield = "channel";

    
 
    $query = "SELECT qstats.reportmonthly.labelreport as lastapp,DAY(datetime) AS hari
                        , COUNT(jumlah) AS total_data,SUM(Seconds) as Seconds from( select event,datetime,real_uniqueid as jumlah,0 Seconds from qstats.queue_stats_mv where (queue='60012' or queue='60013')
                         union 
                        select disposition as event,calldate,uniqueid as jumlah,0 AS seconds from asteriskcdrdb.cdr where dst in ('60012','60011') union select 'CONNECTA' as event,calldate,uniqueid as jumlah,billsec AS seconds from asteriskcdrdb.cdr where dst in ('60012','60011') 
                        union 
                        select 'NEWANSWERED',a.calldate,a.uniqueid,0 Seconds from( select recordingfile,SUM(duration) as Ringtime,calldate,uniqueid from( SELECT substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) AS chan1,asteriskcdrdb.cdr.* FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 HAVING chan1 in ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016', 'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029') ) as a where a.disposition='ANSWERED' group by recordingfile ) as a where Ringtime>0 
                        union 
                        SELECT 'OUTBOUND',a.calldate,a.uniqueid,0 Seconds from( select substring(channel,1,locate('-',channel,1)-1) AS chan1, billsec, calldate,uniqueid, (time_to_sec(calldate)-(hour(calldate)*3600)+billsec)-3600 AS minute, hour(calldate) AS hour,date_format(calldate,'%Y%m%d') AS fulldate FROM asteriskcdrdb.cdr WHERE substring(channel,1,locate('-',channel,1)-1)<>'' AND (duration-billsec) >=0 
                        HAVING chan1 IN ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016', 'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029') ) as a 
                        union 
                        select 'CALLWITHIN',a.calldate,a.uniqueid,0 Seconds from( select recordingfile,SUM(duration) as Ringtime,calldate,uniqueid from( SELECT substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) AS chan1,asteriskcdrdb.cdr.* FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 HAVING chan1 in ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016', 'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029') ) as a where a.disposition='NO ANSWER' group by recordingfile ) as a where Ringtime>0 
                        union 
                        select 'ENTERQUEUENEWa',calldate,uniqueid as jumlah,0 Seconds from asteriskcdrdb.cdr where dst in ('60012','60011') and dstchannel='' 
                        union 
                        select a.event,a.datetime,a.uniqueid as jumlah,(SELECT g.duration FROM asteriskcdrdb.cdr g WHERE g.uniqueid = a.uniqueid and g.disposition='ANSWERED' ORDER BY uniqueid DESC LIMIT 1) AS Seconds from qstats.queue_stats_full a where a.qname in ('2','3') and a.queue in ('60012','60011') 
                        union 
                        select 'EARLYa',calldate,uniqueid as jumlah,0 Seconds from asteriskcdrdb.cdr where disposition in ('NO ANSWER') and dst in ('60012','60011') and duration between '0' and '9' 
                        union 
                        SELECT 'TOTALCALL',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr 
                        WHERE (duration-billsec) >=0 AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/10010','SIP/10011','SIP/10012','SIP/10013','SIP/10014','SIP/10015','SIP/10016', 'SIP/10017','SIP/10018','SIP/10019','SIP/10020','SIP/10021','SIP/10022','SIP/10023','SIP/10024','SIP/10025','SIP/10026','SIP/10027','SIP/10028','SIP/10029') 
                        union 
                        select 'EARLY',curdate(),0 as jumlah,0 Seconds ) as a left outer join qstats.reportmonthly on qstats.reportmonthly.event_id=a.event WHERE datetime !='' 
                        and labelreport !='' 
                        AND DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE() 
                        GROUP BY DAY(datetime),qstats.reportmonthly.labelreport 
                        ORDER BY qstats.reportmonthly.urutan,DAY(datetime)";
    //echo $query;
    $result = $mysqli -> query($query);

                        if ($result) {
                           
                            $rows = array();
                            while ($row = $result->fetch_assoc()) {
                                $rows[] = $row;
                            }
                            // Free the result set
                            $result->free();
                            // Convert the array to JSON
                            $json_output = json_encode($rows);
                           // $data['DataDetail'] = array($json_output);
                            //echo json_encode($json_output, JSON_PRETTY_PRINT);
                            echo $json_output;
                        } else {
                           
                            echo json_encode(array('error' => $mysqli->error));
                        }

    

    
?>


    

<?php

