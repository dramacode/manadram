<?php

function plays($bdd, $filter) {

    $sql = "SELECT code, title, author FROM play".$filter;
    $array = mselect($sql, $bdd);
    foreach ($array as $play) {
        $plays[$play["code"]] = array(
            "title" => $play["title"],
            "author" => $play["author"]
        );
    }
    if(!isset($plays)){return false;}
    return $plays;
}
?>