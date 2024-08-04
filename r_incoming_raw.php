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




$chanfield      = "dstchannel";
    $otherchanfield = "channel";
 
    $query = "SELECT substring($chanfield,1,locate(\"-\",$chanfield,length($chanfield)-8)-1) AS chan1,";
    $query.= "billsec,duration,duration-billsec as ringtime,src,dst,calldate,disposition,accountcode FROM asteriskcdrdb.cdr ";
    $query.= "WHERE DATE_FORMAT(calldate, '%Y-%m-%d') = '2024-04-01' AND (duration-billsec) >=0  ";
    $query.= "HAVING chan1 in ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014','SIP/101010','SIP/101011','SIP/101012','SIP/101013')  order by null";
    //echo $query;
    $result = $mysqli -> query($query);

    

    //while ($res->fetchInto($row, DB_FETCHMODE_ASSOC)) {
        while ($row = $result->fetch_assoc()) {

        $row['accountcode']="Default";

       
            $group_bill_inbound[$row['accountcode']]  = 0;
            $group_ring_inbound[$row['accountcode']]  = 0;
            $group_calls_inbound[$row['accountcode']] = 0;
      

        if(!isset($billsec[$row['accountcode']][$row['chan1']])) {
            $billsec[$row['accountcode']][$row['chan1']]      = 0;
            $duration[$row['accountcode']][$row['chan1']]     = 0;
            $ringing_inbound[$row['accountcode']][$row['chan1']]      = 0;
            $ringing[$row['accountcode']][$row['chan1']]      = 0;
            $number_calls_inbound[$row['accountcode']][$row['chan1']] = 0;
            $number_calls_outbound[$row['accountcode']][$row['chan1']] = 0;
            $missed_inbound[$row['accountcode']][$row['chan1']]       = 0;
        }

        if(!isset($number_calls_outbound[$row['accountcode']][$row['chan1']])) {
            $number_calls_outbound[$row['accountcode']][$row['chan1']]=0;
        }

        if(!isset($number_calls_inbound[$row['accountcode']][$row['chan1']])) {
            $number_calls_inbound[$row['accountcode']][$row['chan1']]=0;
        }

        if(!isset($number_calls_outbound[$row['accountcode']][$row['chan1']])) {
            $number_calls_outbound[$row['accountcode']][$row['chan1']]=0;
        }

        if(!isset($number_calls[$row['accountcode']][$row['chan1']])) {
            $number_calls[$row['accountcode']][$row['chan1']]=0;
        }

        $billsec[$row['accountcode']][$row['chan1']]  += $row['billsec'];
        $duration[$row['accountcode']][$row['chan1']] += $row['duration'];
        $number_calls_inbound[$row['accountcode']][$row['chan1']]++;
        $number_calls[$row['accountcode']][$row['chan1']]++;

        if(!isset($missed_inbound[$row['accountcode']][$row['chan1']])) { $missed_inbound[$row['accountcode']][$row['chan1']]=0; }

        $ringing_inbound[$row['accountcode']][$row['chan1']]+=$row['ringtime'];
        $ringing[$row['accountcode']][$row['chan1']]+=$row['ringtime'];
        $total_bill_inbound+=$row['billsec'];
        $total_ring_inbound+=$row['ringtime'];
        $total_calls_inbound++;

        $group_bill_inbound[$row['accountcode']]+=$row['billsec'];
        $group_ring_inbound[$row['accountcode']]+=$row['ringtime'];
        $group_calls_inbound[$row['accountcode']]++;

        $disposition = $row['disposition'];

        if(!isset($missed[$row['accountcode']][$row['chan1']])) { $missed[$row['accountcode']][$row['chan1']]=0;}
        if($disposition<>"ANSWERED" ) {
            $missed_inbound[$row['accountcode']][$row['chan1']]++;
            $missed[$row['accountcode']][$row['chan1']]++;
        }
    }

    $total_calls = $total_calls_inbound + $total_calls_outbound;
    $total_ring  = $total_ring_inbound + $total_ring_outbound;
    $total_bill  = $total_bill_inbound + $total_bill_outbound;

    $group_calls['Default'] = $group_calls_inbound[$row['accountcode']] + $group_calls_outbound[$row['accountcode']];
    $group_ring['Default']  = $group_ring_inbound[$row['accountcode']] + $group_ring_outbound[$row['accountcode']];
    $group_bill['Default']  = $group_bill_inbound[$row['accountcode']] + $group_bill_outbound[$row['accountcode']];

    if($total_calls > 0) {
        $avg_ring_full = $total_ring / $total_calls;
    } else {
        $avg_ring_full = 0;
    }

    $avg_ring_full = number_format($avg_ring_full,0);

    $total_bill_print = $total_bill;//seconds2minutes($total_bill);

    //$start_parts = preg_split("/ /", $appconfig['start']);
    //$end_parts   = preg_split("/ /", $appconfig['end']);

?>
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
        <td valign=top width='50%'>

            <table width='100%' border=0 cellpadding=0 cellspacing=0>
            <caption><?php echo _($rep_title)?></caption>
            <tbody>
            <tr> 
              <td><?php echo _('Number of Calls')?>:</td>
              <td><?php echo $total_calls?> <?php echo _('calls')?></td>
            </tr>
            <tr>
              <td><?php echo _('Total Time')?>:</td>
              <td><?php echo $total_bill_print?></td>
            </tr>
            <tr>
              <td><?php echo _('Avg. ring time')?>:</td>
              <td><?php echo $avg_ring_full?> <?php echo _('secs')?> </td>
            </tr>
            </tbody>
            </table>
        </td>
    </tr>
    </thead>
    </table>
<?php
if($total_calls>0) {
?>
    <br/>
    <a name='1'></a>
    <table width='99%' cellpadding=3 cellspacing=3 border=0 >
    <caption>
    <img src='<?php echo $appconfig['relative_path'];?>asternic_go-up.png' border=0 class='icon' width=16 height=16>
    &nbsp;&nbsp;
    <?php echo _($rep_title) ?>
    </caption>
        <thead>
        <tr>
              <th><?php echo _('User')?></th>
              <th><?php echo _('Total')?></th>
              <th><?php echo _('Incoming')?></th>
              <th><?php echo _('Outgoing')?></th>
              <th><?php echo _('Completed')?></th>
              <th><?php echo _('Missed')?></th>
              <th><?php echo _('% Missed')?></th>
              <th><?php echo _('Duration')?></th>
              <th><?php echo _('%')?> <?php echo _('Duration')?> </th>
              <th><?php echo _('Avg Duration')?></th>
              <th><?php echo _('Total Ring Time')?></th>
              <th><?php echo _('Avg Ring Time')?></th>
        </tr>
        </thead>

<?php

foreach($billsec as $idx=>$key) {
    echo "<tbody>\n";
        //if(count($appconfig['departments'])>1) {

            $group_bill_print = $group_bill[$idx];
  
            if($group_calls[$idx]>0) {
                $avg_ring_group = $group_ring[$idx] / $group_calls[$idx];
            } else {
                $avg_ring_group = 0;
            }

            $avg_ring_group = number_format($avg_ring_group,0);

            $texto  = "<table width=400 border=0 cellpadding=0 cellspacing=0>";
            $texto .= "<caption>".$rep_title."</caption>";
            $texto .= "<tbody>";
            $texto .= "<tr class=\'section\'>";
            $texto .= "<td>Account Code:";
            $texto .= "<td>$idx</td>";
            $texto .= "</tr>";
            $texto .= "<tr>";
            $texto .= "  <td>"._('No Calls').":</td>";
            $texto .= "  <td>".$group_calls[$idx]." "._('calls')."</td>";
            $texto .= "</tr>";
            $texto .= "<tr>";
            $texto .= "  <td>"._('Total Time').":</td>";
            $texto .= "  <td>$group_bill_print</td>";
            $texto .= "</tr>";
            $texto .= "<tr>";
            $texto .= "  <td>"._('Avg Ringtime').":</td>";
            $texto .= "  <td>$avg_ring_group "._('secs')." </td>";
            $texto .= "</tr>";
            $texto .= "</tbody>";
            $texto .= "</table>";
            echo "<tr ><td colspan=10 style='text-align: left;' bgcolor='#cc9900'><a style='color: black;' href='javascript:void();' class='info'>";
            echo "<span>"._('account_code')."</span> $idx </a></td></tr>\n";

      // }

        $data_pdf   = Array();
        $header_pdf = Array (
                           _('User'),
                           _('Calls'),
                           _('Incoming'),
                           _('Outgoing'),
                           _('Missed'),
                           _('Percent'),
                           _('Bill secs'),
                           _('Percent'),
                           _('Avg. Calltime'),
                           _('Ring Time'),
                           _('Avg. Ring')
        );
        $width_pdf=array(35,15,10,10,15,15,25,20,20,20,20);
        $title_pdf=$rep_title;

       $contador=0;
       $query1="";
       $query2="";
       $query3="";

       foreach($key as $chan=>$val) {

            $contavar = $contador +1;
            $cual = $contador % 2;
            if($cual>0) { $odd = " class='odd' "; } else { $odd = ""; }

            $nomuser=$appconfig['canals'][$chan];

            $nomissed = $number_calls[$idx][$chan] - $missed[$idx][$chan];

            $yesmissed   = $missed[$idx][$chan];
            $query1 .= "valA$contavar=$nomissed&valB$contavar=$yesmissed&var$contavar=$nomuser&";
            $query2 .= "val$contavar=".$val."&var$contavar=$nomuser&";
            $query3 .= "valA$contavar=".$number_calls_outbound[$idx][$chan]."&valB$contavar=".$number_calls_inbound[$idx][$chan]."&var$contavar=$nomuser&";

            $ring_time = $duration[$idx][$chan]-$val;

            if($number_calls[$idx][$chan]>0) {
                $avg_ring_time = $ring_time / $number_calls[$idx][$chan];
                if($nomissed>0) {
                    $avg_duration  = $val / $nomissed;
                } else {
                    $avg_duration = 0;
                }
            } else {
                $avg_duration  = 0;
                $avg_ring_time = 0;
            }

            $avg_duration = number_format($avg_duration,0);
            $avg_duration_print = $avg_duration;
            $avg_ring_time = number_format($avg_ring_time,2);
            $time_print = $duration[$idx][$chan];

            $bill_print = $val;
            if($number_calls[$idx][$chan] > 0) {
                $percent_missed = $missed[$idx][$chan] * 100 / $number_calls[$idx][$chan];
            } else {
                $percent_missed = 0;
            }
            $percent_missed = number_format($percent_missed,0)." "._('%');

            $complete_self = $_SERVER['REQUEST_URI'];
            echo "<tr $odd>\n";

            echo "<td style='text-align: left;'><a style='cursor:pointer;' onclick=\"javascript:getRecords('$chan','${appconfig['start']}','${appconfig['end']}','combined','$complete_self');\">";
            echo "<img src='${appconfig['relative_path']}asternic_loading.gif' id='loading$chan' border=0 style=\"visibility: hidden; float: left;\">";
            echo "{$appconfig['canals'][$chan]}</a></td>\n";

            echo "<td>".$number_calls[$idx][$chan]."</td>\n";
            echo "<td>".$number_calls_inbound[$idx][$chan]."</td>\n";
            echo "<td>".$number_calls_outbound[$idx][$chan]."</td>\n";
            echo "<td>".$nomissed."</td>\n";
            echo "<td>".$missed[$idx][$chan]."</td>\n";
            echo "<td align=right>".$percent_missed."</td>\n";
            echo "<td>$bill_print</td>\n";
            $percentage_bill = $val * 100 / $total_bill;
            $percentage_bill = number_format($percentage_bill,2);
            echo "<td>$percentage_bill "._('%')."</td>\n";
            echo "<td>$avg_duration_print</td>\n";
            echo "<td>$ring_time "._('secs')."</td>\n";
            echo "<td>$avg_ring_time "._('secs')."</td>\n";
            echo "</tr>\n";
            echo "<tr style='display: none;' id='$chan'><td colspan=12>";
            echo "<span id='table${chan}table'></span>\n";
            echo "</td></tr>";

            $linea_pdf = Array(
                                 $appconfig['canals'][$chan],
                                 $number_calls[$idx][$chan],
                                 $number_calls_inbound[$idx][$chan],
                                 $number_calls_outbound[$idx][$chan],
                                 $nomissed,
                                 $missed[$idx][$chan],
                                 $percent_missed,
                                 "$bill_print ",
                                 "$percentage_bill "._('%'),
                                 "$avg_duration_print ",
                                 "$ring_time "._('secs'),
                                 "$avg_ring_time "._('secs')
            );
            $data_pdf[]=$linea_pdf;
            $contador++;

       }
       //$query1.="title=".$rep_title."$graphcolor";
       $query1.="title="._($rep_title)."$graphcolorstack&tagA="._('Completed')."&tagB="._('Missed');
       $query2.="title="._('Total Call Duration by User')."$graphcolor";
       $query3.="title="._('Incoming/Outgoing Calls by User')."$graphcolorstack&tagA=$tagA&tagB=$tagB";

}
echo "</tbody>\n";
echo "</table>\n";

$cover_pdf = _('Report Information')."\n";
$cover_pdf.= _('Start Date').": ".$start_parts[0]."\n";
$cover_pdf.= _('End Date').": ".$end_parts[0]."\n";
$cover_pdf.= _('Period').": ".$appconfig['period']." "._('days')."\n\n";
$cover_pdf.= $rep_title."\n";
$cover_pdf.= _('Number of Calls').": ".$total_calls." "._('calls')."\n";
$cover_pdf.= _('Total Time').": ".$total_bill_print." "._('mins')."\n";
$cover_pdf.= _('Avg. ring time').": ".$avg_ring_full." "._('secs')."\n";

//print_exports($header_pdf,$data_pdf,$width_pdf,$title_pdf,$cover_pdf,$appconfig);

echo "<table class='pepa' width='99%' cellpadding=3 cellspacing=3 border=0>\n";
echo "<thead>\n";
echo "<tr><td><hr/></td></tr>";
echo "<tr><td align=center bgcolor='#fffdf3' width='100%'>\n";
//swf_bar($query1,718,433,"chart1",1);
echo "</td></tr><tr><td><hr/></td></tr>\n";
echo "<tr><td align=center bgcolor='#fffdf3' width='100%'>\n";
//swf_bar($query2,718,433,"chart2",0);
echo "</td></tr><tr><td><hr/></td></tr>\n";
echo "<tr><td align=center bgcolor='#fffdf3' width='100%'>\n";
//swf_bar($query3,718,433,"chart3",1);
echo "</td></tr>\n";
echo "</thead>\n";
echo "</table><BR>\n";


} // end if totalbill > 1

?>
</div> <!-- end asterniccontents -->