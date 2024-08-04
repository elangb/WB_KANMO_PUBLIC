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


$add_query = "";

$three_days_ago = date("Y-m-d 00:00:00", strtotime("-3 days", time()));

/*$result = $mysqli -> query("select * from(
select 'CallReceived' TypeNya,COUNT(uniqueid) as jumlah from qstats.queue_stats_full where qname in ('2','3') and event='CONNECT' and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
Union
select 'CallAnswered' TypeNya,COUNT(*) as ValNya from qstats.queue_stats_mv where (queue='60012' or queue='60011') and event in ('COMPLETECALLER','COMPLETEAGENT') and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
Union
select 'CallAbandoned' TypeNya,COUNT(uniqueid) as jumlah from qstats.queue_stats_full where qname in ('2','3') and event='ABANDON' and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()
) as a");

$sql= "SELECT src,dst,lastapp,substring(channel,1,locate(\"-\",channel,1)-1) AS chan1, ";
$sql.="substring(dstchannel,1,locate(\"-\",dstchannel,1)-1) AS chan2, ";
$sql.="billsec, calldate,j1.dial,j2.dial,if(j1.dial is not null and j2.dial is null,'outbound','') as outbound, ";
$sql.="if(j1.dial is null and j2.dial is not null,'inbound','') AS inbound, ";
$sql.="if(j1.dial is not null and j2.dial is not null,'internal','') as internal ";
$sql.="FROM asteriskcdrdb.cdr LEFT JOIN asterisk.devices as j2 on substring(dstchannel,1,locate(\"-\",dstchannel,1)-1) = j2.dial ";
$sql.="LEFT JOIN asterisk.devices as j1 on substring(channel,1,locate(\"-\",channel,1)-1) = j1.dial WHERE calldate>curdate() AND billsec>0 AND disposition='ANSWERED' ";
$sql.="HAVING (outbound<>'' OR inbound<>'' OR internal<>'') AND chan2<>'' ORDER BY calldate DESC";
*/
$sql = "SELECT substring(dstchannel,1,locate(\"-\",dstchannel,1)-1) AS chan1, billsec, calldate,";
$sql.= "billsec, calldate,";
$sql.= "(time_to_sec(calldate)-(hour(calldate)*3600)+billsec)-3600 AS minute, hour(calldate) AS hour,date_format(calldate,'%Y%m%d') AS fulldate ";
$sql.= "FROM asteriskcdrdb.cdr WHERE  substring(dstchannel,1,locate(\"-\",dstchannel,1)-1)<>'' ";
$sql.= "AND DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE() AND (duration-billsec) >=0 ";
$sql.= "HAVING chan1 IN ('SIP/10010','SIP/10011','SIP/10014') ORDER BY calldate";
//echo $sql;
$result = $mysqli -> query($sql);

$distinct_days = 0;
$previous_date = "";
class DataDetail {
    public $Total_Calls;
    public $Total_Inbound_Calls;
    public $Total_Outbound_Calls;
    public $Total_Internal_Calls;
    public $Total_Minutes;
    public $Total_Inbound_Minutes;
    public $Total_Outbound_Minutes;
    public $Total_Internal_Minutes;
    public $Average_Inbound_Call_Duration;
    public $Average_Outbound_Call_Duration;
    public $Average_Internal_Call_Duration;
    // Add more properties as needed
}
while ($row = $result->fetch_assoc()) {
   
    if($row['fulldate']<>$previous_date) {
        $previous_date=$row['fulldate'];
        $distinct_days++;
     }
     $next_hour = $row['hour']+1;
    
     if($next_hour>23) { $next_hour = 0; }

     if(!isset($dur[$row['chan1']][$row['fulldate']][$row['hour']])) {
         $dur[$row['chan1']][$row['fulldate']][$row['hour']]=0;
     }

     if(!isset($num[$row['chan1']][$row['fulldate']][$row['hour']])) {
         $num[$row['chan1']][$row['fulldate']][$row['hour']]=0;
     }

     if(!isset($dur[$row['chan1']][$row['fulldate']][$next_hour])) {
         $dur[$row['chan1']][$row['fulldate']][$next_hour]=0;
     }

     if(!isset($num[$row['chan1']][$row['fulldate']][$next_hour])) {
         $num[$row['chan1']][$row['fulldate']][$next_hour]=0;
     }

     if($row['minute']>0) {
         // duration overflows hour
         $dur[$row['chan1']][$row['fulldate']][$next_hour]+=$row['minute'];
         $dur[$row['chan1']][$row['fulldate']][$row['hour']]+=($row['billsec']-$row['minute']);
         $num[$row['chan1']][$row['fulldate']][$next_hour]++;
         $num[$row['chan1']][$row['fulldate']][$row['hour']]++;
     } else {
         $dur[$row['chan1']][$row['fulldate']][$row['hour']]+=$row['billsec'];
         $num[$row['chan1']][$row['fulldate']][$row['hour']]++;
     }

 
}
$query = "SELECT hour(calldate) AS hour, count(*) AS count, SUM(billsec) AS seconds FROM asteriskcdrdb.cdr ";
    $query.= "WHERE  substring(dstchannel,1,locate(\"-\",dstchannel,1)-1)<>'' ";
    $query.= "AND DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE() AND (duration-billsec) >=0 ";
    $query.= "AND ( substring(dstchannel,1,locate(\"-\",dstchannel,1)-1) IN ('SIP/10010','SIP/10011','SIP/10014') OR substring(channel,1,locate(\"-\",channel,1)-1) IN ('SIP/10010','SIP/10011','SIP/10014'))";
    $query.= "GROUP BY 1 ORDER BY calldate";

    $result = $mysqli -> query($query);

   

    for($i=0;$i<24;$i++) {
        $disthour[$i]['callcount']=0;
        $disthour[$i]['seconds']=0;
    }

    //while ($res->fetchInto($row, DB_FETCHMODE_ASSOC)) {
    while ($row = $result->fetch_assoc()) {
        $disthour[$row['hour']]['callcount']=$row['count'];
        $disthour[$row['hour']]['seconds']=$row['seconds'];
    }

?>
<table width='99%' cellpadding=3 cellspacing=3 border=0>
    <thead>
    <tr>
        <td valign=top width='50%'>
            <table width='100%' border=0 cellpadding=0 cellspacing=0>
            <caption><?php echo _('Report Information')?></caption>
            <tbody>
            <tr>
                   <td><?php echo _('Start Date')?>:</td>
                   <td><?php echo $start_parts[0]?></td>
            </tr>
            </tr>
            <tr>
                   <td><?php echo _('End Date')?>:</td>
                   <td><?php echo $end_parts[0]?></td>
            </tr>
            <tr>
                   <td><?php echo _('Period')?>:</td>
                   <td><?php echo $appconfig['period']?> <?php echo _('days')?></td>
            </tr>
            </tbody>
            </table>
        </td>
        <td width='50%'>
              &nbsp;
        </td>
    </tr>
    </thead>
    </table>
       
    <div id="asternicmain">
<div id="asterniccontents">

    <table width='99%' cellpadding=3 cellspacing=3 border=0>
    <thead>
    <tr>
        <td valign=top width='50%'>
            <table width='100%' border=0 cellpadding=0 cellspacing=0>
            <caption><?php echo _('Report Information')?></caption>
            <tbody>
            <tr>
                   <td><?php echo _('Start Date')?>:</td>
                   <td><?php echo $start_parts[0]?></td>
            </tr>
            </tr>
            <tr>
                   <td><?php echo _('End Date')?>:</td>
                   <td><?php echo $end_parts[0]?></td>
            </tr>
            <tr>
                   <td><?php echo _('Period')?>:</td>
                   <td><?php echo $appconfig['period']?> <?php echo _('days')?></td>
            </tr>
            </tbody>
            </table>
        </td>
        <td width='50%'>
              &nbsp;
        </td>
    </tr>
    </thead>
    </table>
<?php

    // Distribution per Hour
    echo "<hr/><table>\n";
    echo "<caption>"._('Call Distribution per Hour')."</caption>";
    echo "<thead>"; 
    echo "<tr>";
    echo "<th>"._('Hour')."</th>";
    echo "<th>"._('Call Count')."</th>";
    echo "<th>"._('Duration')."</th>";
    echo "</tr></thead>\n";
    echo "<tbody>";

    $query1 = "";
    $contador = 0;
    foreach ($disthour as $hour=>$key) {
        $contavar = $contador+1;
        $hour_range = sprintf("%02d:00 - %02d:59",$hour,$hour);
        $query1 .= "val$contavar=".$disthour[$hour]['callcount']."&var$contavar=$hour_range&";
        echo "<tr><td>$hour_range</td><td>".$disthour[$hour]['callcount']."</td><td>".$disthour[$hour]['seconds']."</td></tr>\n";
        $contador++;
    }
    echo "</tbody></table><br/>";
    $query1.="title="._('Call Distribution per Hour')."$graphcolor";

    echo "<table class='pepa' width='99%' cellpadding=3 cellspacing=3 border=0>\n";
    echo "<thead>\n";
    echo "<tr><td><hr/></td></tr>";
    echo "<tr><td align=center bgcolor='#fffdf3' width='100%'>\n";
    //swf_bar($query1,718,433,"chart1",0);
    echo "</td></tr>\n";
    echo "</thead>\n";
    echo "</table><BR>\n";

    // Distribution Diagrams
    if($appconfig['period']>1) {
        foreach ($dur as $chann=>$vel) {
            echo "<h2>".$appconfig['canals'][$chann]."</h2>";
            echo "<table>\n<thead>"; 
            echo "<tr><th></th><th colspan=25>"._('Hour of day (8 means 08h00 - 08h59)')."</th></tr>\n";
            echo "<tr><th>Date</th>";
            for ($hour=0;$hour<24;$hour++) {
                echo "<th>$hour</th>";
            }    
            echo "<th>Total</th></tr></thEAD><TBODY>\n";
            foreach ($vel as $date=>$val) {
                $dayprint = _($day_of_week[$date]);
                $dateprint=$dayprint." ".substr($date,0,4)."-".substr($date,4,2)."-".substr($date,6,2);
                echo "<tr><td>$dateprint</td>";
                $total_day = 0;
                for ($hour=0;$hour<24;$hour++) {
                    if(!isset($dur[$chann][$date][$hour])) { $dur[$chann][$date][$hour]=0;}
                    if(!isset($num[$chann][$date][$hour])) { $num[$chann][$date][$hour]=0;}
                    $numcolor = intval(($dur[$chann][$date][$hour]/60)/10);
                    if((intval($dur[$chann][$date][$hour]/60))==0) { $numcolor=6; }
                    $minutes_this_hour = intval($dur[$chann][$date][$hour]/60);
                    $total_day+=$minutes_this_hour;
                    echo "<td bgcolor='$colorete[$numcolor]'>$minutes_this_hour</td>";
                }
                echo "<td><b>$total_day "._('mins')."</b></td></tr>\n";
            }
        echo "</tbody></table>";
        }
    } else {
      // For 1 day reports, list each channel in a row instead of a new table
      echo "<table>\n<thead>"; 
      echo "<tr><th></th><th colspan=25>"._('Hour of day (8 means 08h00 - 08h59)')."</th></tr>\n";
      echo "<tr><th>User</th>";
      for ($hour=0;$hour<24;$hour++) {
         echo "<th>$hour</th>";
      }    
      echo "<th>"._('total')."</th></tr></thead><tbody>\n";
      foreach ($dur as $chann=>$vel) {
          foreach ($vel as $date=>$val) {
            echo "<tr><td>".$appconfig['canals'][$chann]."</td>";
            $total_day = 0;
            for ($hour=0;$hour<24;$hour++) {
                if(!isset($dur[$chann][$date][$hour])) { $dur[$chann][$date][$hour]=0; }
                if($dur[$chann][$date][$hour]=="") { $dur[$chann][$date][$hour]=0;}
                if(!isset($num[$chann][$date][$hour])) { $num[$chann][$date][$hour]=0; }
                if($num[$chann][$date][$hour]=="") { $num[$chann][$date][$hour]=0;}
                // echo "$hour ".intval($dur[$chann][$date][$hour]/60)." - ".$num[$chann][$date][$hour]."<BR>" ;
                $numcolor = intval(($dur[$chann][$date][$hour]/60)/10);
                if((intval($dur[$chann][$date][$hour]/60))==0) { $numcolor=6; }
                $minutes_this_hour = intval($dur[$chann][$date][$hour]/60);
                $total_day+=$minutes_this_hour;
                echo "<td bgcolor='$colorete[$numcolor]'>$minutes_this_hour</td>";
            }
            echo "<td><b>$total_day "._('mins')."</b></td></tr>\n";
          }
      }
    echo "</table>";
    }
?>
</div>
</div>
<hr/>
<div id='asternicfooter'>
<div style='float:right;'><a href='https://www.asternic.net' border=0><img src='<?php echo $appconfig['relative_path'];?>/asternic_cdr_logo.jpg' alt='asternic cdr' border=0></a></div>
</div>
</div> <!-- end div asternic content -->
<div style='clear:both;' class='content'></div>