<?php

function searchPattern($needle, $haystack, $dfields, $corpus, $fields) {


    //PATAB
    $patab = '<table class="pattern">';
    foreach ($_POST["pattern"] as $character) {
        $patab.= '<tr>';
        foreach ($character as $configuration) {
            $patab.= ($configuration == "P") ? '<td class="configuration speaking"></td>' : '<td class="configuration absent"></td>';
        }
        $patab.= '</tr>';
    }
    $patab.= '</table>';

    //RESULTS
    
    //print_r($fields);

    $results = array();
    foreach ($haystack as $key => $hay) {

        //echo count($hay["pattern"]);
        
        //loop through char of first pattern

        
        //print_r($hay);

        
        if (count($hay["pattern"]) == count($needle)) {
            foreach ($needle as $key1 => $char1) {

                //loop through char of second pattern
                foreach ($hay["pattern"] as $key2 => $char2) {

                    // pour obtenir la bonne longueur de motif :
                    
                    //                    $input = array("a", "b", "c", "d", "e");

                    
                    //$output = array_slice($input, 0, 3);      // retourne "a", "b", et "c"

                    
                    if ($char1 == $char2) {

                        //if char from first pattern has the same sequence as char from the second pattern :
                        $true = true;

                        //pour chaque requte xpath
                        
                        //remove char from the second pattern so that it won't be considered when we loop through the other chars from the first pattern

                        unset($hay["pattern"][$key2]);

                        //do not check other char from the second pattern (not essential : just to speed up the process)
                        break;
                    } else {

                        //if char from first pattern doesn't have the same sequence, check another char from the second pattern
                        $true = false;
                    }
                }

                //if no char from the second pattern has the same sequence as a char from the second one, exit
                
                if (!$true) break;
            }
        } else {
            $true = false;
        }
        
        if ($true) {
            $results[$key] = $corpus[$hay["id"]["play"]];

            //string et ajouter -+5
            $results[$key]["act"] = $hay["id"]["act"];
            $results[$key]["scene"] = $hay["id"]["scene"];
            $results[$key]["sceneId"] = $hay["id"]["sceneId"];
            $results[$key]["string"] = $hay["id"]["string"];
             $results[$key]["allocc"] = $hay["id"]["sceneId"];
            if ($_POST["xpath"]["xpath-0"]) {
	            
                $dom = new DOMDocument();
//                 $string = file_get_contents("http://dramacode.github.io/tcp5/" . $hay["id"]["play"] . ".xml");
//                 echo $string;
                //$dom->load("../tcp5/" . $hay["id"]["play"] . ".xml");
                $dom->load("http://dramacode.github.io/tcp5/" . $hay["id"]["play"] . ".xml");
                $xp = new DOMXPath($dom);
                $xp->registerNamespace("tei", "http://www.tei-c.org/ns/1.0");
                $conf = $dom->getElementById($results[$key]["sceneId"]);
//                 print_r($conf);
                $i = 0;
                foreach ($_POST["xpath"] as $xkey => $request) {
                    
                    if (!$request) {
                        continue;
                    }
                    
                    $xpath = $xp->query($request, $conf);
                    $xpath = $xpath ? $xpath->length : "Requte invalide";
                    $results[$key][$xkey] = $xpath;
                    $results[$key]["xpath"]["xpath-".$i] = $xpath;
                    $i++;
                }
            }
        }
    }

    //foreach ($results as $key=>$result){
    //    foreach ($results as $key2=>$result2){
    //        if(($result2["play"] == $result["play"]) and ($result2["sceneId"] != $result["sceneId"])){
    //            $results[$key]["allocc"].= "+".$result2["sceneId"];
    //        }
    //    }
    //}

    //OCCURRENCES
    $occurrences = array();
    $occurrences["n"] = count($results);
    $occurrences["total"] = count($haystack);
    $occurrences["percentage"] = round(($occurrences["n"] / $occurrences["total"]) * 100, 2);

    //TABLES
    $tables = array();
    foreach ($dfields as $kfield => $dfield) {
        foreach ($fields[$kfield] as $valueId => $field) {
            foreach ($results as $result) {
                
                if (!isset($tables[$kfield][$valueId])) {
                    $tables[$kfield][$valueId]["n"] = 0;
                }
                //echo $result[$kfield]."-".$field["value"]."<br/>";
                if ($result[$kfield] == $field["field"]) {
                    
                    $tables[$kfield][$valueId]["n"]++;
                }
            }
        }
    }
    //echo "<pre>";print_r($dfields);
    //echo "<pre>";print_r($fields);
    //echo "<pre>";print_r($tables);
    //echo "<pre>";print_r($results);
   
    $array=array();
    //gŽrer ici la proportion et l'absence de donnŽe
    foreach ($dfields as $kfield => $dfield) {
        foreach ($tables[$kfield] as $valueId => $table) {
            $array[$kfield][$valueId]["n"] = $tables[$kfield][$valueId]["n"];
            $array[$kfield][$valueId]["total"] = $fields[$kfield][$valueId]["value"];
            $array[$kfield][$valueId]["percentage"] = ($fields[$kfield][$valueId]["value"] > 0) ? round((($array[$kfield][$valueId]["n"] / $fields[$kfield][$valueId]["value"]) * 100), 2): "false";
            $array[$kfield][$valueId]["allocc"] = "";
        }
    }
    $tables = $array;
    //gŽrer les XPath
    foreach ($results as $key=>$result){
        foreach ($results as $key2=>$result2){
            //echo "<br/>".$result2["play"]."-".$tables["play"][$result["play"]]["allocc"]. "-" .$result2["sceneId"];
            if(($result2["play"] == $result["play"]) and (strpos($tables["play"][$result["play"]]["allocc"], $result2["sceneId"]) ===false)){
                //echo "a";
                $tables["play"][$result["play"]]["allocc"] .= $result2["sceneId"]."+";
            }
        }
    }
    
    foreach($results as $result){
        $i=0;
        foreach($result["xpath"] as $key=>$xpath){
                if (!isset($tables["xpath"][$key])) {
                    $tables["xpath"][$key][$xpath]["n"] = 1;
                }else{
                    $tables["xpath"][$key][$xpath]["n"]++;
                }
        $i++; 
        }
    }
    foreach($tables["xpath"] as $key=>$table){
        ksort($tables["xpath"][$key]);
    }
        //echo "<pre>";print_r($_POST);print_r($tables);

    //echo "<pre>";print_r($_POST);
   

    //CSV
    $lustra = $fields["lustrum"];
    $csv = '"Date,Pourcentage\n"+';
    $json = "";
    foreach ($tables["lustrum"] as $key=>$lustrum) {
        $csv.= '"' . substr($key, 0, 4) . ',' . $lustrum["percentage"] . '\n" +';
        $json .= "['".$key."', ".($lustrum["percentage"]/100).",".$fields["lustrum"][$key]["value"]."],";
    }
    $csv = rtrim($csv, "+");
    return array(
        "patab" => $patab,
        "results" => $results,
        "occurrences" => $occurrences,

        "tables" => $tables,
        
        "csv" => $csv,
        "json" => $json
        
    );
}
?>