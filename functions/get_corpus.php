<?php
function get_corpus(){
    $bdd = connect();
$sql = "SELECT * FROM play ORDER BY code ASC";
$corpus = mselect($sql, $bdd);
return $corpus;
}
?>