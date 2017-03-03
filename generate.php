<?php
include_once ("functions/pattern.php");
set_time_limit(600);
ini_set('memory_limit', '-1');
if(!isset($_GET["c"])){return;}
$p = (isset($_GET["p"]))? $_GET["p"]:false ;
$c = $_GET["c"];
$group = (isset($_GET["group"]))? $_GET["group"]:false ;
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'/><link rel='stylesheet' href='css/generate.css'/></head><body>";
$valids = array();
if($p){
    generate($p, $c, $group, $valids);
}else{
    generate_all($c, $valids);
}
echo "<pre>";
print_r($valids);
echo "</body></html>";
function generate($p, $c, $group = false, &$valids) {

    
    //générer tous les motifs
    $i = 0;
    $max = bcpow(2, ($p * $c));
    $cp = $c * $p;
    $patterns = $duplicates = $empty_char = $duplicate_char = $empty_conf = $repeat_conf = array();
    //test
    echo "<h2>".$c." configurations, ".$p." personnages</h2><p>2^(cp) = ".$max." motifs possibles, de 0 à ".decbin($max-1)."<sub>2</sub></p>";
    echo "<table><tr style='vertical-align:top;'><td><table><tr style='vertical-align:top;'><td><h3>Motifs possibles</h3>".$max." motifs</td><td><h3>Motifs possibles réordonnés</h3>".$max." motifs<br/><br/></td></tr>";
    //
    
    while ($i < $max) { //vérifier que je n'ai pas besoin de <=

        $pattern = str_pad(decbin($i) , $cp, 0, STR_PAD_LEFT);

        //vérifier que je n'ai pas besoin de (c*p)+1
        
        //on dit que pattern = pers1(conf1, conf2), pers2(conf1, conf2) et flip_pattern = conf1(pers1, pers2), conf2(pers1, pers2)

        $pattern = binflat($pattern, $c);

        //test
        echo "<tr><td>".print_pattern($pattern)."</td>";
        //
        
        $pattern = flatorder($pattern);
        //test
        echo "<td>".print_pattern($pattern)."</td></tr>";
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
    echo "<td class='filtered'><h3>Motifs possibles dédoublonnés</h3>" . print_patterns($patterns)."</td>";
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
    echo "<td class='filtered'><h3>Motifs avec au moins un personnage toujours absent</h3>" . print_patterns($empty_char);
    echo "<h3>Motifs avec au moins deux personnages dont la distance = 0</h3>" . print_patterns($duplicate_char);
    echo "<h3>Motifs avec au moins une configuration sans personnage</h3>" . print_patterns($empty_conf);
    echo "<h3>Motifs avec au moins une configuration identique à la suivante</h3>" . print_patterns($repeat_conf);
    echo "</td><td class='filtered'><h3>Motifs valides</h3>" . print_patterns($patterns, true)."</td></tr></table>";
    //
    
    return array_values($patterns);
}

function generate_all($c, &$valids) {

    $max = (bcpow(2, $c)); //nombre max de personnages possibles ayant une distance > 0 avec ce nombre de configuration
    //test
    echo "<h1>".$c." configurations, (2^c)-1 = ".($max-1)." personnages distincts possibles</h1>";
    //
    
    $p = 1;
    $total = 0;
    $patterns = array();
    while ($p < $max) {
        //test
        echo "<h2>Motifs à " . $p . " personnages</h2>";
        //
        
        $patterns = array_merge($patterns, generate($p, $c, false, $valids));
        
        //test
        $total = count($patterns);
        //
        $p++;
    }
    
    //test
    echo "<h2>Total : ".$total." motifs valides</h2>";
    //
    return $patterns;
}
//lister tous les string
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
