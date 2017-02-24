<?php
function get_filters($bdd){
    $filters = array();
$sql = "SELECT DISTINCT code, author, title FROM play ORDER BY code ASC";
$results = mselect($sql,$bdd);
foreach($results as $result){
    $filters["code"][$result["code"]]["author"] = $result["author"];
    $filters["code"][$result["code"]]["title"] = $result["title"];
    
}
$sql = "SELECT DISTINCT author FROM play  ORDER BY author ASC";
$results = mselect($sql,$bdd);
foreach($results as $result){
    $filters["author"][] = $result["author"];
}
$sql = "SELECT DISTINCT genre FROM play  ORDER BY genre ASC";
$results = mselect($sql,$bdd);
foreach($results as $result){
    $filters["genre"][] = $result["genre"];
}
$sql = "SELECT DISTINCT created FROM play  ORDER BY created ASC";
$results = mselect($sql,$bdd);
foreach($results as $result){
    $filters["lustrum"][] = $result["created"];
}
return $filters;
}
?>