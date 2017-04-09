<?php

function search($needle, $length, $confidents, $group, $bdd, $filter) {



    //prend un motif, la longueur, les options confident et group, les tables  renvoyer
    $select_patterns = "SELECT * FROM pattern WHERE (str_code = '" . $needle . "' OR str_code = '" . $needle . "/' OR str_code = '/" . $needle . "' OR str_code = '/" . $needle . "/') AND l = " . $length . " AND c = " . $confidents . " AND g = " . $group.$filter;
    $results = mselect($select_patterns, $bdd);

    $array = array();
    foreach ($results as $key => $result) {
        $select_play = "SELECT * FROM play WHERE id ='" . $result["play_id"] . "'";
        $results[$key] = array_merge($result, select($select_play, $bdd));
        
        if ($_GET["xpath"]["xpath-0"]) {
            $dom = new DOMDocument();
            $dom->load("../tcp5/" . $results[$key]["code"] . ".xml");
            $xp = new DOMXPath($dom);
            $xp->registerNamespace("tei", "http://www.tei-c.org/ns/1.0");
            $conf = $dom->getElementById($results[$key]["conf_id"]);
            $i = 0;
            foreach ($_GET["xpath"] as $xkey => $request) {
                //echo $i;
                if (!$request) {
                    continue;
                }
                $xpath = $xp->query($request, $conf);
                $xpath = $xpath ? $xpath->length : "Requête invalide";
                $results[$key][$xkey] =  $results[$key]["xpath"]["xpath-" . $i] = $xpath;
                $i++;
            }
        }
    }

    //RESULTS
    return $results;

    //print_r($fields);
    
}
?>