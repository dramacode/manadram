<?php

function doPost($bdd, $fields, $group, $confidents, $filter, $filter_play) {

    //$a = microtime(true);





    
 
    $needles = get_needle();
    $all_results = array();
    foreach ($needles as $needle) {
        $length = $needle["length"];
        $needle = $needle["needle"];
        $all_results[$needle]["length"] = $length;
        $sql = "SELECT SUM(value) FROM stats WHERE l = " . $length . " AND c = " . $confidents . " AND g = " . $group . $filter;
        $result = select($sql, $bdd);
        $global_n = reset($result);
        $plays = plays($bdd, $filter_play);        
        if (!$plays) {
            echo "Aucune pièce ne remplit ces critères.";
            return;
        }
        $all_results[$needle]["plays"] = $plays;
        $all_results[$needle]["results"] = search($needle, $length, $confidents, $group, $bdd, $filter);
        $all_results[$needle]["count"] = count($all_results[$needle]["results"]);
        $all_results[$needle]["proportion"] = round(($all_results[$needle]["count"] / $global_n) * 100, 2);
    }
    return $all_results;
    //$b = microtime(true);
    //$time = round($b - $a, 2);
  
}
?>