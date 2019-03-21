<?php

function occurrences($patterns) {
    //prend une liste de pattern d'une même pièce, même longueur, mêmes options
    //renvoie, pour chaque motif, la liste des occurrences
    $array = array();

    //renvoie un array de tous les scene_id contenant le même motif
    foreach ($patterns as $pattern) {


        $array[$pattern["trim_code"]][] = $pattern["scene_id"];
    }

    //[id](scene_id, sceneid)
    return $array;

    //occurrences

    //pièces


    //lustre, auteur, genre


    //xpath ? même tpl


}
?>
