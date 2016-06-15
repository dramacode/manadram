<?php
function getDates($files){
    foreach ($files as $file) {
    $d = biblio(basename($file, ".xml"));
    $dates[] = $d["date"];
    }
    $end = max($dates);
    $i = min($dates);
    while($i<=$end){
        $array[] = $i;
        $i++;
    }
    return $array;
}
?>