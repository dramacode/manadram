<?php
function get_corpus($bdd){
$sql = "SELECT * FROM play ORDER BY code ASC";
$corpus = mselect($sql, $bdd);
return $corpus;
}
?>