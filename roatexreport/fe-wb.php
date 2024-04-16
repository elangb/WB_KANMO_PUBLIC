<!doctype html>
<html lang="en">
    <!--data-bs-theme="dark"-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Roatex Recording</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script>
        /*function autoRefresh() {
            window.location = window.location.href;
        }
        setInterval('autoRefresh()', 1000);*/
    </script>
</head>

<body>
    <?php
        $url = "https://crm.uidesk.id/reportroatexasterisk/be-wb/listagent.php?search=".($_GET['search'] ?? "")."&start_date=".($_GET['start_date'] ?? "")."&end_date=".($_GET['end_date'] ?? "");
        // if (isset($_GET['search'])) $url .= "?search=" . $_GET['search'];
        // if (isset($_GET['search'])) $url .= "?search=" . $_GET['search'];
        // if(isset($_GET['start_date']) && !empty($_GET['start_date']) && isset($_GET['end_date']) && !empty($_GET['end_date'])) $url .= " AND (calldate BETWEEN '".$_GET['start_date']."' AND '".$_GET['end_date']." 23:59:59')";
        
        $content = file_get_contents($url);
        $data = json_decode($content, true);
        /*$x = "41";
        $n = intval( $x );
        echo $n;*/
    ?>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                Roatex Wallboard
            </a>
        </div>
    </nav>
    <div class="container-fluid">
        <table class="table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Value</th>
                    <th>Abandonrate %</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                 $urlsum = "https://crm.uidesk.id/reportroatexasterisk/be-wb/agentsummary.php";
                $contentsum = file_get_contents($urlsum);
                $datasum = json_decode($contentsum, true);
                foreach ($datasum as $keysum => $rowsum) { 
                        if($rowsum['TypeNya']=="CallReceived"){
                            $CallReceived=(int)$rowsum['ValNya'];
                        }elseif($rowsum['TypeNya']=="CallAbandoned"){
                            $CallAbandoned=(int)$rowsum['ValNya'];
                        }
                        $a=intval($CallReceived);
                        $b=intval($CallAbandoned);
                        if($a==0){
                            $Abandonrate = "0";
                        }else{
                            $Abandonrate = $a / $b * 100;
                        }
                        
                ?>
                <tr>
                        <td><?= $rowsum['TypeNya'] ?></td>
                        <td><?= $rowsum['ValNya'] ?></td>
                        <td><?= $Abandonrate ?>%</td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="mt-3"></div>
        <table class="table">
            <thead>
                <tr>
                    <th>Extension</th>
                    <th>Status</th>
                    <th>Disposition</th>
                    <th>Customer</th>
                    <th>Agent</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($data as $key => $row) { 
                
                ?>
                <tr>
                        <td><?= $row['extension'] ?></td>
                <?php
                    $urlstats = "https://crm.uidesk.id/reportroatexasterisk/be-wb/agentstats.php?extension=".($row['extension'] ?? "")."";
                $contentstats = file_get_contents($urlstats);
                $datastats = json_decode($contentstats, true);
                foreach ($datastats as $keystats => $rowstats) { 
                    if($rowstats['eventtype']=="CHAN_START"){
                        $agentStats="Ringing";
                    }elseif($rowstats['eventtype']=="ANSWER"){
                        $agentStats="Talking";
                    }elseif($rowstats['eventtype']=="HANGUP"){
                        $agentStats="Ended";
                    }else{
                        $agentStats=$rowstats['eventtype'];
                    }
                ?>
                        
                        <td><?= $agentStats ?></td>
                        
                        
                    
                <?php   }?>
              
                        <td><?= $row['disposition'] ?></td>
                        <td><?= $row['src'] ?></td>
                        <td><?= $row['dst'] ?></td>
                        </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="tssModal" tabindex="-1" aria-labelledby="tssModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tssModalLabel">Transcript</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    
    <script>
        function openTss(uniqueid) {
            alert("Please contact your administration!");
            /*
            $.post("./transcript.php", {uniqueid}, (res) => {
                console.log(res);
                let arr = JSON.parse(res);
                console.log(arr);
                
                let tss = "";
                arr.results.forEach(item => {
                    tss += item.text+"\n\n";
                });
                
                $('#tssModal .modal-body').html(tss);
                $('#tssModal').modal('show');
            })*/
        }
    </script>
</body>

</html>