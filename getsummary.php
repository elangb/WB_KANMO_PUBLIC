<?php
$mysqli = new mysqli("pbx.uidesk.id","root","Uid35k32!Uid35k32!J4y4","asteriskcdrdb");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}


$add_query = "";

$three_days_ago = date("Y-m-d 00:00:00", strtotime("-3 days", time()));
/*$result = $mysqli -> query("select * from(
select 'CallReceived' TypeNya,COUNT(*) as ValNya from asteriskcdrdb.cdr where DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE()
Union
select 'CallAnswered' TypeNya,COUNT(*) as ValNya from asteriskcdrdb.cdr where DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()  and disposition='ANSWERED' 
Union
select 'CallAbandoned' TypeNya,COUNT(*) as ValNya from asteriskcdrdb.cdr where DATE_FORMAT(calldate, '%Y-%m-%d') = CURDATE()  and (disposition='NO ANSWER' or disposition='BUSY') 
) as a");*/

if ($_GET['param'] =='KANMO')
       {
$sqlNya="select 'Outbound' as TypeNya,COUNT(*) as Jumlah from(
  SELECT substring(channel,1,locate('-',channel,1)-1) AS chan1
  FROM asteriskcdrdb.cdr WHERE  substring(channel,1,locate('-',channel,1)-1)<>'' 
  AND DATE(calldate)=CURDATE() AND (duration-billsec) >=0 
  HAVING chan1 IN ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014')
) as a
  UNION
  select 'Inbound' as Jenis,COUNT(*) as JumlahInbound from(
  SELECT substring(dstchannel,1,locate('-',dstchannel,1)-1) AS chan1
  FROM asteriskcdrdb.cdr WHERE  substring(dstchannel,1,locate('-',dstchannel,1)-1)<>'' 
  AND DATE(calldate)=CURDATE() AND (duration-billsec) >=0 
  HAVING chan1 IN ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014')
) as a
UNION
  select 'InboundMissed' as Jenis,COUNT(*) as JumlahInbound from(
  SELECT substring(dstchannel,1,locate('-',dstchannel,1)-1) AS chan1,uniqueid
  FROM asteriskcdrdb.cdr WHERE  substring(dstchannel,1,locate('-',dstchannel,1)-1)<>'' 
  AND DATE(calldate)=CURDATE() AND (duration-billsec) >=0 AND disposition='NO ANSWER'
  HAVING chan1 IN ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014')
  ) as a
  UNION
  select 'InboundAnswered' as Jenis,COUNT(*) as JumlahInbound from(
  SELECT substring(dstchannel,1,locate('-',dstchannel,1)-1) AS chan1
  FROM asteriskcdrdb.cdr WHERE  substring(dstchannel,1,locate('-',dstchannel,1)-1)<>'' 
  AND DATE(calldate)=CURDATE() AND (duration-billsec) >=0 AND disposition='ANSWERED'
  HAVING chan1 IN ('SIP/201010','SIP/201011','SIP/201012','SIP/201013','SIP/201014')
  ) as a
  UNION
  select 'InboundAbandon' as Jenis,COUNT(*) as JumlahInbound from(
  select event from qstats.queue_stats_mv where DATE(datetime)=CURDATE() and event in ('ABANDON')
  ) as a;";
}else{
  $sqlNya="select 'Outbound' as TypeNya,COUNT(*) as Jumlah from(
    SELECT substring(channel,1,locate('-',channel,1)-1) AS chan1
    FROM asteriskcdrdb.cdr WHERE  substring(channel,1,locate('-',channel,1)-1)<>'' 
    AND DATE(calldate)=CURDATE() AND (duration-billsec) >=0 
    HAVING chan1 IN ('SIP/101010','SIP/101011','SIP/101012','SIP/101013')
  ) as a
    UNION
    select 'Inbound' as Jenis,COUNT(*) as JumlahInbound from(
    SELECT substring(dstchannel,1,locate('-',dstchannel,1)-1) AS chan1
    FROM asteriskcdrdb.cdr WHERE  substring(dstchannel,1,locate('-',dstchannel,1)-1)<>'' 
    AND DATE(calldate)=CURDATE() AND (duration-billsec) >=0 
    HAVING chan1 IN ('SIP/101010','SIP/101011','SIP/101012','SIP/101013')
  ) as a
  UNION
    select 'InboundMissed' as Jenis,COUNT(*) as JumlahInbound from(
    SELECT substring(dstchannel,1,locate('-',dstchannel,1)-1) AS chan1,uniqueid
    FROM asteriskcdrdb.cdr WHERE  substring(dstchannel,1,locate('-',dstchannel,1)-1)<>'' 
    AND DATE(calldate)=CURDATE() AND (duration-billsec) >=0 AND disposition='NO ANSWER'
    HAVING chan1 IN ('SIP/101010','SIP/101011','SIP/101012','SIP/101013')
    ) as a
    UNION
    select 'InboundAnswered' as Jenis,COUNT(*) as JumlahInbound from(
    SELECT substring(dstchannel,1,locate('-',dstchannel,1)-1) AS chan1
    FROM asteriskcdrdb.cdr WHERE  substring(dstchannel,1,locate('-',dstchannel,1)-1)<>'' 
    AND DATE(calldate)=CURDATE() AND (duration-billsec) >=0 AND disposition='ANSWERED'
    HAVING chan1 IN ('SIP/101010','SIP/101011','SIP/101012','SIP/101013')
    ) as a
    UNION
    select 'InboundAbandon' as Jenis,COUNT(*) as JumlahInbound from(
    select event from qstats.queue_stats_mv where DATE(datetime)=CURDATE() and event in ('ABANDON')
    ) as a;";
}


$result = $mysqli -> query($sqlNya);

$data = [];
while ($row = $result->fetch_assoc()) {
   
    $data['DataDetail'][] = $row;
}

echo json_encode($data);
?>
