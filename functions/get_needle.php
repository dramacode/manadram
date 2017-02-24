<?php
function get_needle() {
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
    return array(
        "needle" => $needle,
        "length" => $length
    );
}
?>