<?php
include_once ("functions/pattern.php");
set_time_limit(600);
ini_set('memory_limit', '-1');

function generate($p, $c, $group = false) {

    
    //générer tous les motifs
    $i = 0;
    $max = bcpow(2, ($p * $c));
    $cp = $c * $p;
    $patterns = $duplicates = $empty_char = $duplicate_char = $empty_conf = $repeat_conf = array();
    //test
    echo "<h2>".$c." configurations, ".$p." personnages</h2><p>2^(cp) = ".$max." motifs possibles, de 0 à ".decbin($max-1)."<sub>2</sub></p>";
    echo "<table style='border-collapse:collapse;'><tr style='vertical-align:top;'><td style='border:1px solid black;'><table><tr style='vertical-align:top;'><td><h3>Motifs possibles</h3>".$max." motifs</td><td><h3>Motifs possibles réordonnés</h3><br/><br/></td></tr>";
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
    echo "<td style='border:1px solid black;padding:5px;'><h3>Motifs possibles dédoublonnés</h3>" . print_patterns($patterns)."</td>";
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

    //test
    echo "<td style='border:1px solid black;padding:5px;'><h3>Motifs avec au moins un personnage toujours absent</h3>" . print_patterns($empty_char);
    echo "<h3>Motifs avec au moins deux personnages dont la distance = 0</h3>" . print_patterns($duplicate_char);
    echo "<h3>Motifs avec au moins une configuration sans personnage</h3>" . print_patterns($empty_conf);
    echo "<h3>Motifs avec au moins une configuration identique à la suivante</h3>" . print_patterns($repeat_conf);
    echo "</td><td style='border:1px solid black;padding:5px;'><h3>Motifs valides</h3>" . print_patterns($patterns, true)."</td></tr></table>";
    //
    
    return array_values($patterns);
}

function generate_all($c) {

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
        
        $patterns = array_merge($patterns, generate($p, $c));
        
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
//generate_all(3);
generate(3,3);
//string[0];

//C. combinaisons regroupées

//rupture ?


//retour ?


//permanence ?

//alternance ?

//version terminal
//Mairet
?>
