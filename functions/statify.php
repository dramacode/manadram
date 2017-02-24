<?php

function statify(array $results, $field, $length, $group, $confidents, $xpath, $plays, $bdd, $filter, $filter_play) {

    $table = array();
    if ($xpath) {
        foreach ($results as $result) {
            $table[] = $result[$field];
        }
        $table = array_count_values($table);
        return $table;
    }
    $sql = "SELECT DISTINCT " . $field . " FROM play".$filter_play;
    $field_values = mselect($sql, $bdd);
     
    foreach ($field_values as $field_value) {
        $table[$field_value[$field]] = 0;
    }
    ksort($table);
    //echo "<pre>";print_r($table);
    
    //    $table = array();

    foreach ($results as $result) {

        //$table[] = $result[$field];
        $table[$result[$field]]++;

    }

    //$table = array_count_values($table);
    foreach ($table as $key => $row) {
        $sql = "SELECT SUM(value) FROM stats WHERE play_id IN (SELECT id FROM play WHERE " . $field . "= '" . $key . "') AND l = " . $length . " AND c = " . $confidents . " AND g = " . $group.$filter;
        $result = select($sql, $bdd);
        $result = reset($result);
        $table[$key] = array(
            "title" => $key,
            "occurrences" => $row,
            "all" => $result,
            "proportion" => round(($row / $result) , 4) * 100,
        );
        
        if ($field == "code") {
            $table[$key]["title"] = $plays[$key]["title"];
            $table[$key]["author"] = $plays[$key]["author"];
        }
    }
    return $table;
}
?>