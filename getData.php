<?php
    /*
	"Splits/Skills";"INOUT CUST SMG;INOUT PROV SMG"
    "Split/Skill";"ACD Calls";"csplit.INQUEUE";"Agents Staffed";"Agents in AUX";"% Within Service Level";"% Aban Calls";"Outbound Aban Calls";"Aban Calls"
    "INOUT PROV SMG";0;0;8;6;0;0;0;0
    "INOUT CUST SMG";0;0;3;1;0;0;0;0
    "State";"Login ID";"Extn";"Preference";"AUX Reason";"AUXINTIME";"Direction";"Time";"VDN";"Split/Skill";"ACD Calls";"ACWINTIME";"ACW Time";"Aban Calls";"Accepted Interrupts";"StateAgent"
    "AUX";"Suzmita Septianing P";"4629";"LVL";"Isi Foam_Discharge";0;"0";25681;"";"INOUT PROV SMG";0;0;0;0;0;"AUX"
    "AUX";"Misael fridayanto";"4620";"LVL";"System Aux";0;"0";482;"";"INOUT PROV SMG";0;0;0;0;0;"AUX"
    "AUX";"Indah Kartikasari";"4621";"LVL";"Isi Foam_Discharge";0;"0";28463;"";"INOUT PROV SMG";0;0;0;0;0;"AUX"
    "AUX";"Salsa Zakiya Nafsa";"4622";"LVL";"0";0;"0";28867;"";"INOUT PROV SMG";0;0;0;0;0;"AUX"
    "AVAIL";"Silvy Adytia Anggrai";"4630";"LVL";"";0;"0";338;"";"INOUT PROV SMG";0;0;0;0;0;"AVAIL"
    "AUX";"Istichomah";"4627";"LVL";"Isi Foam_Discharge";0;"0";15030;"";"INOUT PROV SMG";0;0;0;0;0;"AUX"
    "AVAIL";"Deva Kartika Aprilia";"4610";"LVL";"";0;"0";520;"";"INOUT CUST SMG";0;0;0;0;0;"AVAIL"
    "AVAIL";"Dian Pratiwi";"4608";"LVL";"";0;"0";3324;"";"INOUT CUST SMG";0;0;0;0;0;"AVAIL"
    "AUX";"Laurensia Carolline";"4628";"LVL";"Isi Foam_Discharge";0;"0";16845;"";"INOUT PROV SMG";0;0;0;0;0;"AUX"
    "AVAIL";"Achmad Rijal Chilmi";"4619";"LVL";"";0;"0";130;"";"INOUT PROV SMG";0;0;0;0;0;"AVAIL"
    "AUX";"Milenia Oktoriani Ra";"4609";"LVL";"Rest Room_Toilet";0;"0";167;"";"INOUT CUST SMG";0;0;0;0;0;"AUX"

	*/
	
	$string =file_get_contents("allDataAuto.txt");

    $baris = preg_split('/\r\n|\n|\r/', trim($string));

    $data = [];
    foreach ($baris as $key => $value) {
        if($key <= 3) {
            $items = explode(";", filter($value));
            $item_value = array_splice($items, 1);
            $data['Head'][filter($items[0])] = (count($item_value) == 1) ? $item_value[0] : $item_value;
        } else {
            $items = explode(";", filter($value));
            
            if($key == 4) {
                $groups = $items;
                $groupKey = $groups[0];
            }

            if($key > 4) { 
                for ($i=0; $i < count($items)-1; $i++) { 
                    $detail[filter($groups[(1+$i)])] = filter($items[(1+$i)]);
                }
                
                $data['DataDetail'][] = $detail;
            }
        }
    }

    function filter($teks)
    {
        return trim(preg_replace('/\s+/', ' ', preg_replace("/[^A-Za-z0-9\ \;]/", " ", $teks)));
    }
    
    // print_r($data);

    echo json_encode($data);