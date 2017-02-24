<?php
function json($table){
    //$lustra = $fields["lustrum"];
    //$csv = '"Date,Pourcentage\n"+';
    $json = "";
    foreach ($table as $key=>$lustrum) {
        //$csv.= '"' . substr($key, 0, 4) . ',' . $lustrum["percentage"] . '\n" +';
        $json .= "['".$key."', ".($lustrum["proportion"]/100).",".$lustrum["all"]."],";
    }
    //$csv = rtrim($csv, "+");
    return $json;
}
?>