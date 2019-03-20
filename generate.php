<?php
include_once ("functions/pattern.php");
set_time_limit(600);
ini_set('memory_limit', '-1');
$valids = array();

if (isset($argv)) {
    $lb = "\n";
    $a = microtime(true);
    if (isset($argv[2])) {
        $group = isset($argv[3]) ? $arg[3] : false;
        ob_start();
        generate($argv[1], $argv[2], $argv[3], $valids);
        ob_end_clean();
        valids($valids, $lb);
    } else {
        ob_start();
        generate_all($argv[1], $valids);
        ob_end_clean();
        valids($valids, $lb);
    }
    $b = microtime(true);
    echo "\nTemps d'exécution : ".round($b-$a, 2)." secondes\n";

} else {
    $lb = "<br/>";
    
    if (!isset($_GET["c"])) {
        return;
    }
    $p = (isset($_GET["p"])) ? $_GET["p"] : false;
    $c = $_GET["c"];
    //$entracts = (isset($_GET["entracts"])) ? $_GET["entracts"] : false;
    $group = (isset($_GET["group"])) ? $_GET["group"] : false;
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'/><link rel='stylesheet' href='css/generate.css'/><title>Motifs dramatiques</title></head><body>";
    
    if ($p) {
        generate($c, $p, $group, $valids);
        valids($valids, $lb);
    } else {
        generate_all($c, $valids);
        valids($valids, $lb);
    }
    echo "</body></html>";
}


function valids($valids, $lb) {
    $valids = entracts($valids);

    //echo "<pre>";print_r($valids);
    $total = count($valids);
    if($lb == "\n"){echo "\nTotal : " . $total . " motifs valides\n";}else{
        echo "<h2>Total : " . $total . " motifs valides</h2>";
    }
    //print_r($valids);
    foreach ($valids as $pattern) {
        $pattern = flatmulti($pattern);
        $pattern = multiflip($pattern);
        $pattern = intcode($pattern);
        $pattern = multistring($pattern);
        echo $pattern . $lb;
    }
}

function entracts($valids){
    $array = array();
    //ne marche que pour l = 3, un seul entracte dans le motif
    foreach($valids as $pattern){
	//line = char
	$characters_n = count($pattern);
	$pattern = flatmulti($pattern);
	$pattern = multiflip($pattern);
	$pattern = multiflat($pattern);//line = conf
	$line = str_repeat("0",$characters_n);
	$new = array($pattern[0], $line, $pattern[1], $pattern[2]);
	$new = flatmulti($new);
	$new = multiflip($new);
	$new = multiflat($new);
	$array[] = $new;
	$new = array($pattern[0], $pattern[1], $line, $pattern[2]);
	$new = flatmulti($new);
	$new = multiflip($new);
	$new = multiflat($new);
	$array[] = $new;
    }
    return $array;
}

function generate($c, $p, $group = false, &$valids) {


    //générer tous les motifs
    $i = 0;
    $max = pow(2, ($p * $c));
    $cp = $c * $p;
    $patterns = $duplicates = $empty_char = $duplicate_char = $empty_conf = $repeat_conf = array();

    //test
    echo "<h2>s = " . $c . " scènes, p = " . $p . " personnages</h2><p>2<sup>sp</sup> = " . $max . " motifs possibles, de 0 à " . decbin($max - 1) . "<sub>2</sub></p>";
    echo "<table><tr style='vertical-align:top;'><td><table><tr style='vertical-align:top;'><td><h3>Motifs possibles</h3>" . $max . " motifs</td><td><h3>Motifs possibles réordonnés</h3>" . $max . " motifs<br/><br/></td></tr>";

    //
    while ($i < $max) { //vérifier que je n'ai pas besoin de <=

        $pattern = str_pad(decbin($i) , $cp, 0, STR_PAD_LEFT);

        //vérifier que je n'ai pas besoin de (c*p)+1
        
        //on dit que pattern = pers1(conf1, conf2), pers2(conf1, conf2) et flip_pattern = conf1(pers1, pers2), conf2(pers1, pers2)

        $pattern = binflat($pattern, $c);

        //test
        echo "<tr><td>" . print_pattern($pattern) . "</td>";

        //
        $pattern = flatorder($pattern);

        //test
        echo "<td>" . print_pattern($pattern) . "</td></tr>";

        //
        $pattern = flatbin($pattern);
        $patterns[] = $pattern;
        $i++;
    }

    //test
    echo "</tr></table></td>";

    //
    
    //virer les doublons de motif et afficher à part

    $patterns = array_values(array_unique($patterns));
    $array = array();
    foreach ($patterns as $pattern) {
        $array[] = binflat($pattern, $c);
    }
    $patterns = $array;

    //test
    echo "<td class='filtered'><h3>Motifs possibles dédoublonnés</h3>" . print_patterns($patterns) . "</td>";

    //
    foreach ($patterns as $key => $pattern) {

        //personnage absent
        
        if (flatempty($pattern)) {
            unset($patterns[$key]);

            //test
            $empty_char[] = $pattern;

            //
            
        }

        //grouper les personnages
        
        if (flatduplicate($pattern)) { //

            unset($patterns[$key]);

            //test
            $duplicate_char[] = $pattern;

            //
            
        }
        $flip_pattern = flatmulti($pattern);
        $flip_pattern = multiflip($flip_pattern);
        $flip_pattern = multiflat($flip_pattern);

        //configuration vide
        
        if (flatempty($flip_pattern)) {
            unset($patterns[$key]);

            //test
            $empty_conf[] = $pattern;

            //
            
        }

        //configurations successives identiques
        
        if (flatrepeat($flip_pattern)) {
            unset($patterns[$key]);

            //test
            $repeat_conf[] = $pattern;

            //
            
        }
    }
    $valids = array_merge($valids, $patterns);

    //test
    echo "<td class='filtered'><h3>Motifs exclus</h3><h4>Motifs avec au moins un personnage toujours absent</h4>" . print_patterns($empty_char);
    echo "<h4>Motifs avec au moins deux personnages concomittants</h4>" . print_patterns($duplicate_char);
    echo "<h4>Motifs avec au moins une scène sans personnage</h4>" . print_patterns($empty_conf);
    echo "<h4>Motifs avec au moins une scène identique à la suivante</h4>" . print_patterns($repeat_conf);
    echo "</td><td class='filtered'><h3>Motifs valides</h3>" . print_patterns($patterns, true) . "</td></tr></table>";

    //
    return array_values($patterns);
}

function generate_all($c, &$valids) {

    $max = (bcpow(2, $c)); //nombre max de personnages possibles ayant une distance > 0 avec ce nombre de configuration

    
    //test

    echo "<h1>s = " . $c . " scènes, 2<sup>s</sup>-1 = " . ($max - 1) . " personnages distincts possibles</h1>";

    //
    $p = 1;
    $total = 0;
    $patterns = array();
    while ($p < $max) {

        //test
        echo "<h2>Motifs à " . $p . " personnages</h2>";

        //
        $patterns = array_merge($patterns, generate($c, $p, false, $valids));

        //test
        
        //

        $p++;
    }

    //test
    
    //

    return $patterns;
}

//lenteur

//créer des motifs pour les entractes. Le faire en second, sur string (entracte à l'intérieur du motif)


//A-B/A/A-C


//A-B/A-B-C/A-C


//A/A-B/A


//A/A-B/B


//A/A-B/A-B-C


//A-B-C/A-B/A


//A/A-B-C/A-B


//A-B/A-B-C/A


//C. combinaisons regroupées


//rupture ?


//retour ?


//permanence ?


//alternance ?


//version terminal


//Mairet


?>
