<?php
set_time_limit(20000);

function roman($num) {

    $n = intval($num);
    $res = '';

    /*** roman_numerals array  ***/
    $roman_numerals = array(
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1
    );
    foreach ($roman_numerals as $roman => $number) {

        /*** divide to get  matches ***/
        $matches = intval($n / $number);

        /*** assign the roman char * $matches ***/
        $res.= str_repeat($roman, $matches);

        /*** substract from the number ***/
        $n = $n % $number;
    }

    /*** return the res ***/
    return $res;
}

function roundUpToAny($n, $x = 5) {

    $date = (round($n) % $x === 0) ? round($n) : round(($n + $x / 2) / $x) * $x;
    //echo $date." ; ";
    //$date = $date -1;
    return $date;
}

function lustrum($date){
    //echo $date." ; ";
    $date++;
    $lustrum = roundUpToAny($date);
    //echo $lustrum." ; ";
    //echo $lustrum;
    $lustrum = ($lustrum-5)."-".($lustrum-1);
    return $lustrum;
    //echo $lustrum."<br/>";
}
//$dates = array(1630, 1631, 1632, 1633, 1634, 1635, 1636, 1637, 1638, 1639, 1640);
//foreach ($dates as $date){
//    lustrum($date);
//}
//echo lustrum(1631);

function in_array_r($needle, $haystack, $strict = false) {

    foreach ($haystack as $item) {
        
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}
function normalize($string){
    $search = array("'", '"', " ", "’", ",", "é", "è", "ê", "ë", "É", "È", "Ê", "Ë", "â", "Â", "ô", "ö", "Ô", "Ö", "î", "ï", "Î", "Ï", "œ", "æ", "Œ", "Æ");
    $replace = array("", "", "", "","", "e", "e", "e", "e", "E", "E", "E", "E", "a", "A", "o", "o", "O", "O", "i", "i", "I", "I", "oe", "ae", "OE", "AE");
    return str_replace($search, $replace, $string);
}
?>