<?php

function getAgentLogin($startDate, $endDate)
{
    function getData($startDate, $endDate)
    {
		//echo "https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID=".$startDate."&TrxUserName=".$endDate."&TrxAction=UIDESK122";
        // $query_fetch = json_decode(file_get_contents('data2.1.json'), true);
        $query_fetch = file_get_contents("https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID=".$startDate."&TrxUserName=".$endDate."&TrxAction=UIDESK122");
        $query_fetch = strip_tags($query_fetch);
		$query_fetch = json_decode($query_fetch, true);
        // die();
        $datas = [];
        foreach ($query_fetch as $key => $data) {
            if ($data['LoginDescription'] == "Login") {
                $loginDate = preg_replace('/[^0-9]/', '', $data['LoginDate']);
                $hour = date('H', $loginDate);

                $datas[$hour][$data['LoginUserName'] ?? ''] = $data;
            }
        }
        return $datas;
    }

    $datas = getData($startDate, $endDate);
	//var_dump($datas);
    ksort($datas);
    $new = [];
    for ($i = 0; $i < 24; $i++) {
        $h = str_pad($i, 2, '0', STR_PAD_LEFT);
        $new[$h] = count($datas[$h] ?? []) ?? 0;
    }
	//var_dump($new);
    return $new;
}
// echo "<pre>";
// print_r(getAgentLogin('2024-02-11', '2024-02-15'));
// echo "</pre>";
