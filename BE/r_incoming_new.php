<?php
ini_set("error_reporting", E_ALL);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('GMT');
$mysqli = new mysqli("pbx.uidesk.id","root","Uid35k32!Uid35k32!J4y4","asteriskcdrdb");
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
  
  //OUTBOUND
  // First outbound
  $chanfield      = "channel";
  $otherchanfield = "dstchannel";
  $rep_title      = "Incoming / Outgoing";

  $paramValue ='' ;
  $sql2='';
//die($paramValue);


  
  
                if ($_GET['param'] =='KANMO')
                {
                            $query= "SELECT qstats.reportmonthly.labelreport as lastapp,DAY(datetime) AS hari, COUNT(jumlah) AS total_data
                            ,SUM(Seconds) as Seconds from( select event,datetime,real_uniqueid as jumlah,0 Seconds from qstats.queue_stats_mv 
                            where (queue='60012' or queue='60013') 
                            union 
                            SELECT 'FIXANSWERED',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr 
                            WHERE (duration-billsec) >=0 and disposition='ANSWERED' AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014')
                            union 
                            SELECT 'ABANDON 1',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 and disposition='NO ANSWER' AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014') 
                            union 
                            SELECT 'BUSY 1',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 and disposition='BUSY' AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014') 
                            union 
                            SELECT 'OUTBOUND',a.calldate,a.uniqueid,0 Seconds from( select substring(channel,1,locate('-',channel,1)-1) AS chan1, billsec, calldate,uniqueid, (time_to_sec(calldate)-(hour(calldate)*3600)+billsec)-3600 AS minute, hour(calldate) AS hour,date_format(calldate,'%Y%m%d') AS fulldate FROM asteriskcdrdb.cdr WHERE substring(channel,1,locate('-',channel,1)-1)<>'' AND (duration-billsec) >=0 HAVING chan1 IN ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014') ) as a 
                            union
                            SELECT 'TOTALCALL',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014')
                            union
                            select 'EARLY',curdate(),0 as jumlah,0 Seconds ) as a left outer join qstats.reportmonthly on qstats.reportmonthly.event_id=a.event WHERE datetime !='' and labelreport !='' 
                            AND DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE() 
                            GROUP BY DAY(datetime),qstats.reportmonthly.labelreport ORDER BY qstats.reportmonthly.urutan,DAY(datetime);";
                          
                               
                            }else{

                                $query= "SELECT qstats.reportmonthly.labelreport as lastapp,DAY(datetime) AS hari, COUNT(jumlah) AS total_data
                                ,SUM(Seconds) as Seconds from( select event,datetime,real_uniqueid as jumlah,0 Seconds from qstats.queue_stats_mv 
                                where (queue='60010' or queue='60013') 
                                union 
                                SELECT 'FIXANSWERED',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr 
                                WHERE (duration-billsec) >=0 and disposition='ANSWERED' AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/101010','SIP/101011','SIP/101012','SIP/101013')
                                union 
                                SELECT 'ABANDON 1',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 and disposition='NO ANSWER' AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/101010','SIP/101011','SIP/101012','SIP/101013') 
                                union 
                                SELECT 'BUSY 1',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 and disposition='BUSY' AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/101010','SIP/101011','SIP/101012','SIP/101013') 
                                union 
                                SELECT 'OUTBOUND',a.calldate,a.uniqueid,0 Seconds from( select substring(channel,1,locate('-',channel,1)-1) AS chan1, billsec, calldate,uniqueid, (time_to_sec(calldate)-(hour(calldate)*3600)+billsec)-3600 AS minute, hour(calldate) AS hour,date_format(calldate,'%Y%m%d') AS fulldate FROM asteriskcdrdb.cdr WHERE substring(channel,1,locate('-',channel,1)-1)<>'' AND (duration-billsec) >=0 HAVING chan1 IN ('SIP/101010','SIP/101011','SIP/101012','SIP/101013') ) as a 
                                union
                                SELECT 'TOTALCALL',calldate,uniqueid as jumlah,0 Seconds FROM asteriskcdrdb.cdr WHERE (duration-billsec) >=0 AND substring(dstchannel,1,locate('-',dstchannel,length(dstchannel)-8)-1) in ('SIP/101010','SIP/101011','SIP/101012','SIP/101013')
                                union
                                select 'EARLY',curdate(),0 as jumlah,0 Seconds ) as a left outer join qstats.reportmonthly on qstats.reportmonthly.event_id=a.event WHERE datetime !='' and labelreport !='' 
                                AND DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE() 
                                GROUP BY DAY(datetime),qstats.reportmonthly.labelreport ORDER BY qstats.reportmonthly.urutan,DAY(datetime);";

                                //         $sql2 ="SELECT 'Outbound' as lastapp, count(*) as total_data 
                                // FROM asteriskcdrdb.cdr 
                                // WHERE substring(channel,1,locate("-",channel,1)-1)<>'' 
                                // AND DATE(calldate)='2024-04-30' 
                                // AND (duration-billsec) >=0 
                                // AND substring(channel,1,locate("-",channel,1)-1) IN ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014')
                                // ORDER BY calldate";
                            }
                  //die($sql2);
                  //$result2 = $mysqli -> query($sql2);
                          
              
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

                       
                                               
                        // if ($result) {
                           
                        //     $rows = array();
                        //     while ($row = $result->fetch_assoc()) {
                        //         $rows[] = $row;
                        //     }
                        //     // Free the result set
                        //     $result->free();
                        //     // Convert the array to JSON
                        //     $json_output = json_encode($rows);
                        //    // $data['DataDetail'] = array($json_output);
                        //     //echo json_encode($json_output, JSON_PRETTY_PRINT);
                        //     echo $json_output;
                        // } else {
                           
                        //     echo json_encode(array('error' => $mysqli->error));
                        // }

                        // Create the array mapping
                        

  
  
?>
