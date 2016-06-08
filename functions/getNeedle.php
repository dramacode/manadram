<?php
function getNeedle() {

    $needle = $_POST["pattern"];
    $search = array(
        "A",
        "P",
        "PM",
        "APM"
    );
    $replace = array(
        0,
        1,
        1,
        1
    );
    $array = array();
    $notEmpty = array();
    foreach ($needle as $character => $configurations) {
        $i = 0;
        foreach ($configurations as $key => $configuration) {
            $array[$character][$i] = ($configuration == "A") ? 0 : 1;
            
            if ($configuration != "A") {
                $notEmpty[$key] = true;
            }
            $i++;
        }
        
        if (!in_array(1, $array[$character])) {
            unset($array[$character]);
        }
    }
    $needle = $array;

    // echo "<pre>";
    
    //

    
    //

    
    //print_r($needle);

    
    //print_r($notEmpty);

    
    //unset une colonne si entirement vide

    $array = array();
    foreach ($needle as $character => $configurations) {
        $i = 0;
        foreach ($configurations as $key => $configuration) {
            
            if (isset($notEmpty[$key])) {
                $array[$character][$i] = $configuration;
                $i++;
            }
        }
    }
    $needle = $array;

    //print_r($needle);
    
    //echo "</pre>";

    return $needle;
}
?>