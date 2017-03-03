<?php
function get_needle() {
    if(($_GET["str_code"])){
        $needles = explode("\n", $_GET["str_code"]);
        $array = array();
        foreach($needles as $needle){
            $needle = trim($needle);
            if(!$needle){continue;}
            $length = explode("/",$needle);
            $length = array_filter($length);
            $length = count($length);
            //si n'est pas un motif valide
            //$length = 2;
            //echo $length;
            //$length = 2;
            $array[] = array("needle"=>$needle, "length"=>$length);
        }
        return $array;
    }
    
    //A1
    $needle = $_GET["pattern"];
    $needle = array_values($needle);
    $needle = multiflat($needle);
    //virer les personnages vides
    $needle = flatremoveempty($needle);

    $needle = flatmulti($needle);
    $needle = multiflip($needle);
    //1A
    $needle = multiflat($needle);
    //virer les conf qui se répètent
    $needle = flatremoverepeat($needle);
    $length = flatcountconf($needle);
    //réordonner
    $needle = flatmulti($needle);
    $needle = multiflip($needle);
    $needle = multiflat($needle);
    $needle = flatorder($needle);

    //convertir en bin
    //$needle = flatbin($needle);
    $needle = flatmulti($needle);
    $needle = multiflip($needle);
    $needle = intcode($needle);
    $needle = multistring($needle);
    //$needle = bindec($needle);
    //echo $needle;
    return array(array(
        "needle" => $needle,
        "length" => $length
    ));
}
?>