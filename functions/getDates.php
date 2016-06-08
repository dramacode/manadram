<?php
function getDates($files){
    foreach ($files as $file) {
    $dates[] = biblio(basename($file, ".xml"))["date"];
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