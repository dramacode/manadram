<?php

function getPatternsN($haystack, $filter, $value){
    $i=0;
    foreach($haystack as $hay){
        if($hay["id"][$filter]==$value){$i++;}
    }
    return $i;
//compter aussi le nombre global
//impossible de compter pour les xpath, ou le faire systŽmatiquement
//no data
}
?>