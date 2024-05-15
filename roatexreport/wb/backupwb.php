<!DOCTYPE html>
<html lang="en">

<head>
    <title>Wallboard Inbound Roatex</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="icon" type="image/ico" href="favicon.ico" />

    <link href="css/stylesheets.css" rel="stylesheet" type="text/css" />

    <script type='text/javascript' src='js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='js/plugins/uniform/jquery.uniform.min.js'></script>

    <script type='text/javascript' src='js/plugins/knob/jquery.knob.js'></script>
    <script type='text/javascript' src='js/plugins/sparkline/jquery.sparkline.min.js'></script>
    <script type='text/javascript' src='js/plugins/flot/jquery.flot.js'></script>
    <script type='text/javascript' src='js/plugins/flot/jquery.flot.resize.js'></script>

    <script type='text/javascript' src='js/plugins.js'></script>
    <script type='text/javascript' src='js/actions.js'></script>
    <script type='text/javascript' src='js/charts.js'></script>
    <script type='text/javascript' src='js/settings.js'></script>
    <script>
        
        
        function loadData(){
            var objToday = new Date(),
        	weekday = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
        	dayOfWeek = weekday[objToday.getDay()],
        	domEnder = function() { var a = objToday; if (/1/.test(parseInt((a + "").charAt(0)))) return "th"; a = parseInt((a + "").charAt(1)); return 1 == a ? "st" : 2 == a ? "nd" : 3 == a ? "rd" : "th" }(),
        	dayOfMonth = today + ( objToday.getDate() < 10) ? '0' + objToday.getDate() + domEnder : objToday.getDate() + domEnder,
        	months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
        	curMonth = months[objToday.getMonth()],
        	curYear = objToday.getFullYear(),
        	curHour = objToday.getHours() > 12 ? objToday.getHours() - 12 : (objToday.getHours() < 10 ? "0" + objToday.getHours() : objToday.getHours()),
        	curMinute = objToday.getMinutes() < 10 ? "0" + objToday.getMinutes() : objToday.getMinutes(),
        	curSeconds = objToday.getSeconds() < 10 ? "0" + objToday.getSeconds() : objToday.getSeconds(),
        	curMeridiem = objToday.getHours() > 12 ? "PM" : "AM";
        var today = curHour + ":" + curMinute + "." + curSeconds + curMeridiem + " " + dayOfWeek + " " + dayOfMonth + " of " + curMonth + ", " + curYear;
            /*document.getElementsById('jamTime').innerHTML   = curHour + ":" + curMinute + "." + curSeconds + curMeridiem;
            document.getElementsById('dateTime').innerHTML   = dayOfWeek + " " + dayOfMonth + " of " + curMonth + ", " + curYear;*/
            document.getElementById("jamTime").textContent=curHour + ":" + curMinute + "." + curSeconds + curMeridiem;
            document.getElementById("dateTime").textContent=dayOfWeek + " " + dayOfMonth + " of " + curMonth + ", " + curYear;
        }
    </script>
</head>

<body class="bg-img-light-ltr" data-settings="open" onload="loadData()">
    <?php
                 $urlsum = "https://uidesk.id/Asterisk/roatex/be-wb/agentsummary.php";
                $contentsum = file_get_contents($urlsum);
                $datasum = json_decode($contentsum, true);
                foreach ($datasum as $keysum => $rowsum) { 
                        if($rowsum['TypeNya']=="CallReceived"){
                            $CallReceived=$rowsum['ValNya'];
                        }elseif($rowsum['TypeNya']=="CallAbandoned"){
                            $CallAbandoned=$rowsum['ValNya'];
                        }elseif($rowsum['TypeNya']=="CallAnswered"){
                            $CallAnswered=$rowsum['ValNya'];
                        }
                        $a=intval($CallReceived);
                        $b=intval($CallAbandoned);
                        $c=intval($CallAnswered);
                        /*$a=intval(5);
                        $b=intval(6);
                        $c=intval(8);*/
                        if($a==0){
                            $Abandonrate = "0";
                        }else{
                            $Abandonrate = ($b / $a) * 100;
                        }
                        
                ?>
                
                <?php
                }
                ?>
    <div class="container">

        <div class="row">
            
            <div class="col-md-3">
                <div style="padding-top:0px;" class="block block-transparent ">
                   
                    <div class="content">
                        <img height="70px;" src="img/sunrise.png">
                        <h1 style="font-weight: 500; font-size:20px;">Inbound<br><span id="jamTime"></span></h1><br>
                        <h1 style="position: relative; top:-30px;font-size:20px;"><span id="dateTime"></span></h1>
                        <hr style="width: 120px; float:left;">
                    </div>
                </div>
                
                <div class="block block-transparent">
                    <div class="content">
                       
                        <div>
                            
                            <h2 style="font-weight: 600;">Call Received</h2>
                            <h1 style="font-weight: 600; font-size:120px; color:#ffffff; "><?php echo $a; ?></h1>
                            <p
                                style="font-size:20px;color: #2d384a;position:relative;background-color: white;width: 140px;padding-left: 10px;top:-20px;">
                                Answered
                                <strong style="font-weight: 600;"> <?php echo $c; ?> </strong></p>
                                <p
                                style="font-size:20px;color: red;position:relative;background-color: white;width: 140px;padding-left: 10px;top:-20px;">
                                Abandoned
                                <strong style="font-weight: 600;"> <?php echo $b; ?> </strong></p>
                            
                        </div>
                        <!--<div class="sparkline">
                            <span sparkWidth="250" sparkHeight="120" sparkLineColor="#ffffff" sparkFillColor="false"
                                sparkSpotColor="#ffffff" sparkMinSpotColor="#ffffff" sparkMaxSpotColor="#ffffff"
                                sparkHighlightSpotColor="#ffffff" sparkHighlightLineColor="#FFF" sparkSpotRadius="3"
                                sparkLineWidth="2">5,6,7,9,9,5,3,2,2,4,6,7</span>
                        </div>-->
                    </div>
                </div>

            </div>

            <div class="col-md-3">
                <div style="padding-top:20px; " class="block block-transparent">
                    <center>
                        <!--<div class="content">

                            <div class="content">
                                <div class="knob">
                                    <input style="margin-left:0px;" type="text" data-fgColor="#009688" data-min="0"
                                        data-max="100" data-width="250" data-height="250" value="0"
                                        data-angleOffset="-125" data-angleArc="250" / disabled>
                                </div>
                            </div>
                            <div style="position:relative; top:-40px;">
                                <h2 style="font-weight: 600;">( % ) Service Level</h2>
                            </div>
                        </div>-->
                        <div class="block">
                    <h2 style="font-weight: 600;">Agent States</h2>
                    <?php
                        $countTalking = 0;
                        $urlstats = "https://uidesk.id/Asterisk/roatex/be-wb/agentstats.php?extension=".($row['extension'] ?? "")."";
                        $contentstats = file_get_contents($urlstats);
                        $datastats = json_decode($contentstats, true);
                        foreach ($datastats as $keystats => $rowstats) { 
                            if($rowstats['eventtype']=="CHAN_START"){
                                $agentStats="Ringing";
                            }elseif($rowstats['eventtype']=="ANSWER" or $rowstats['eventtype']=="BRIDGE_ENTER"){
                                $agentStats="Talking";
                                $countTalking ++;
                            }elseif($rowstats['eventtype']=="HANGUP"){
                                $agentStats="Ended";
                            }else{
                                $agentStats=$rowstats['eventtype'];
                                
                            }
                        }
                    ?>
                    <div class="head bg-dot30">
                        <div class="head-panel nm">
                            <div class="hp-info pull-left">
                                <div class="hp-icon">
                                    <img height="25px;" src="img/profile-user_notready.png">
                                </div>
                                <span style="font-size:20px; padding-bottom:5px; font-weight:700;"
                                    class="hp-main">0</span>
                                <span style="font-size:15px;" class="hp-sm">Not Ready</span>
                            </div>
                            <div class="hp-info pull-left">
                                <div class="hp-icon">
                                    <img height="25px;" src="img/profile-user.png">
                                </div>
                                <span style="font-size:20px; padding-bottom:5px; font-weight:700;"
                                    class="hp-main"><?php echo $countTalking;?></span>
                                <span style="font-size:15px;" class="hp-sm">Talking</span>
                            </div>
                            <div class="hp-info pull-left">
                                <div class="hp-icon">
                                    <img height="25px;" src="img/profile-user_ready.png">
                                </div>
                                <span style="font-size:20px; padding-bottom:5px; font-weight:700;"
                                    class="hp-main">0</span>
                                <span style="font-size:15px;" class="hp-sm">Ready</span>
                            </div>

                        </div>
                    </div>
                </div>

                
                    </center>
                </div>



                <div class="block block-transparent">
                     <center>
                        <div class="content">

                            <div class="content">
                                <div class="knob">
                                    <input style="margin-left:0px;" type="text" data-fgColor="#f51214" data-min="0"
                                        data-max="100" data-width="250" data-height="250" value="<?php echo $Abandonrate;?>"
                                        data-angleOffset="-125" data-angleArc="250" / disabled>
                                </div>
                            </div>
                            <div style="position:relative; top:-40px;">
                                <h2 style="font-weight: 600;">( % ) Call Abandon Rate</h2>
                            </div>
                        </div>
                    </center>
                    <div style="display:none;" class="block block-drop-shadow">
                        <div class="head bg-dot20">
                            <h1>Incoming Call Distributions</h1>
                            <h5>Service distribution channels.</h5>
                            <div class="head-panel tac">
                                <div class="sparkline">
                                    <span sparkType="pie" sparkWidth="150" sparkHeight="150"><?php echo $a;?>,<?php echo $b;?>,<?php echo $c;?></span>
                                </div>
                            </div>
                            <div class="head-panel">
                                <div class="hp-info hp-simple pull-left hp-inline">
                                    <span style="font-size:18px;" class="hp-main"><span class="icon-circle"></span>
                                        CallReceived</span>
                                </div>
                                <div class="hp-info hp-simple pull-left hp-inline">
                                    <span style="font-size:18px;" class="hp-main"><span
                                            class="icon-circle text-info"></span> CallAbandoned</span>
                                </div>
                                <div class="hp-info hp-simple pull-left hp-inline">
                                    <span style="font-size:18px;" class="hp-main"><span
                                            class="icon-circle text-primary"></span> CallAnswered</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


            <div class="col-md-3">
                <div style="padding-top:0px; " class="block block-transparent">
                    <h2 style="font-weight: 600; padding-top:50px;">List Agent</h2>
                <div style="margin-top:20px;" class="block block-drop-shadow">
                    <div class="content content-transparent np">
                        <div class="list list-contacts">
                            <?php
                            $url = "https://uidesk.id/Asterisk/roatex/be-wb/listagent.php?search=".($_GET['search'] ?? "")."&start_date=".($_GET['start_date'] ?? "")."&end_date=".($_GET['end_date'] ?? "");
                            
                            $content = file_get_contents($url);
                            $data = json_decode($content, true);
                            foreach ($data as $key => $row) { 
                            if($row['extension']=="10010"){
                                $agentName="Agent1";
                            }elseif($row['extension']=="10011"){
                                $agentName="Agent2";
                            }elseif($row['extension']=="10012"){
                                $agentName="Agent3";
                            }elseif($row['extension']=="10013"){
                                $agentName="Agent4";
                            }else{
                                $agentName=$row['extension'];
                            }
                            ?>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/agentroatex.png" width="40" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name"><?= $agentName ?></span>
                                    <div style="font-size:13px; color:rgb(128, 83, 0);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                       <?php
                                        $countTalking = 0;
                                        $urlstats = "https://uidesk.id/Asterisk/roatex/be-wb/agentstats.php?extension=".($row['extension'] ?? "")."";
                                        $contentstats = file_get_contents($urlstats);
                                        $datastats = json_decode($contentstats, true);
                                        foreach ($datastats as $keystats => $rowstats) { 
                                            if($rowstats['eventtype']=="CHAN_START"){
                                                $agentStats="Ringing";
                                            }elseif($rowstats['eventtype']=="ANSWER" or $rowstats['eventtype']=="BRIDGE_ENTER"){
                                                $agentStats="Talking";
                                                $countTalking++;
                                            }elseif($rowstats['eventtype']=="HANGUP"){
                                                $agentStats="Ended";
                                            }else{
                                                $agentStats=$rowstats['eventtype'];
                                            }
                                        ?>
                                      
                                        <?php echo $agentStats;  }?>
                                    </div>
                                </div>
                            </a>
                            <?php } ?>
                            <!--<a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/helen_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name"> Michel G</span>
                                    <div style="font-size:13px; color:green;" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Ready
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/olga_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name">Ananta Michel</span>
                                    <div style="font-size:13px; color:rgb(255, 255, 255);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Talking
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/olga_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name">Siera Uye</span>
                                    <div style="font-size:13px; color:rgb(255, 255, 255);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Talking
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/helen_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name">Jeny Go</span>
                                    <div style="font-size:13px; color:rgb(255, 255, 255);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Talking
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/alexey_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name">Yueana Re</span>
                                    <div style="font-size:13px; color:rgb(255, 255, 255);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Talking
                                    </div>
                                </div>
                            </a>-->
                        </div>
                    </div>
                </div>
                    

                </div>
                
                
            </div>
            <div class="col-md-3">
                
                <h2 style="font-weight: 600; padding-top:50px;">&nbsp;</h2>
                <div style="margin-top:20px;" class="block block-drop-shadow">
                    <div class="content content-transparent np">
                        <div class="list list-contacts">
                            <?php
                            $url = "https://uidesk.id/Asterisk/roatex/be-wb/listagent2.php?search=".($_GET['search'] ?? "")."&start_date=".($_GET['start_date'] ?? "")."&end_date=".($_GET['end_date'] ?? "");
                            
                            $content = file_get_contents($url);
                            $data = json_decode($content, true);
                            foreach ($data as $key => $row) { 
                            if($row['extension']=="10010"){
                                $agentName="Agent1";
                            }elseif($row['extension']=="10011"){
                                $agentName="Agent2";
                            }elseif($row['extension']=="10012"){
                                $agentName="Agent3";
                            }elseif($row['extension']=="10013"){
                                $agentName="Agent4";
                            }else{
                                $agentName=$row['extension'];
                            }
                            ?>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/agentroatex.png" width="40" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name"><?= $agentName ?></span>
                                    <div style="font-size:13px; color:rgb(128, 83, 0);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                       <?php
                                        $countTalking = 0;
                                        $urlstats = "https://uidesk.id/Asterisk/roatex/be-wb/agentstats.php?extension=".($row['extension'] ?? "")."";
                                        $contentstats = file_get_contents($urlstats);
                                        $datastats = json_decode($contentstats, true);
                                        foreach ($datastats as $keystats => $rowstats) { 
                                            if($rowstats['eventtype']=="CHAN_START"){
                                                $agentStats="Ringing";
                                            }elseif($rowstats['eventtype']=="ANSWER" or $rowstats['eventtype']=="BRIDGE_ENTER"){
                                                $agentStats="Talking";
                                                $countTalking++;
                                            }elseif($rowstats['eventtype']=="HANGUP"){
                                                $agentStats="Ended";
                                            }else{
                                                $agentStats=$rowstats['eventtype'];
                                            }
                                        ?>
                                      
                                        <?php echo $agentStats;  }?>
                                    </div>
                                </div>
                            </a>
                            <?php } ?>
                            <!--<a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/helen_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name"> Michel G</span>
                                    <div style="font-size:13px; color:green;" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Ready
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/olga_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name">Ananta Michel</span>
                                    <div style="font-size:13px; color:rgb(255, 255, 255);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Talking
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/olga_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name">Siera Uye</span>
                                    <div style="font-size:13px; color:rgb(255, 255, 255);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Talking
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/helen_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name">Jeny Go</span>
                                    <div style="font-size:13px; color:rgb(255, 255, 255);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Talking
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-item">
                                <div class="list-info">
                                    <img src="img/example/user/alexey_s.jpg" class="img-circle img-thumbnail">
                                </div>
                                <div class="list-text">
                                    <span style="font-size:18px;" class="list-text-name">Yueana Re</span>
                                    <div style="font-size:13px; color:rgb(255, 255, 255);" class="list-text-info"><i
                                            class="icon-circle"></i>
                                        Talking
                                    </div>
                                </div>
                            </a>-->
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>