<?php
set_time_limit(20000);

//transformation

function multiflat(array $pattern) {


    //implode+loop
    $i = 0;
    foreach ($pattern as $key => $row) {
        $pattern[$key] = implode($row);
        $i++;
    }
    return $pattern;
}

function flatmulti(array $pattern) {


    //explode+loop
    $i = 0;
    foreach ($pattern as $key=>$row) {
        $pattern[$key] = str_split($row);
        $i++;
    }
    return $pattern;
}

function flatbin(array $pattern) {


    //implode
    $pattern = implode($pattern);
    return $pattern; //+c?

    
}

function binflat($pattern, $c) {


    //str_split
    $pattern = str_split($pattern, $c);
    return $pattern;
}

function multiflip(array $pattern) {
    $array = array();
    foreach ($pattern as $krow => $row) {
        foreach ($row as $kcell => $cell) {
            $array[$kcell][$krow] = $cell;
        }
    }
    $pattern = $array;
    return $pattern;
}

function intid(array $pattern, $characters = false) {


    //remplace les valeur numériques par les identifiants
    
    //1A

    
    //clef alpha

    $array = array();
    $i = 0;
    foreach ($pattern as $configuration) {
        
        if (implode($configuration) == 0) {
            $array[$i][] = ""; //entracte

            
        } else {
            foreach ($configuration as $key=>$character) {
                
                if ($character) {
                    $array[$i][] = $characters ? $characters[$key]: $key;
                }
            }
        }
        $i++;
    }
    $pattern = $array;
    return $pattern;
}

function intcode(array $pattern) {


    //remplace les valeur numériques par des clefs alpha
    
    //1A

    
    //clef alpha

    $array = array();
    $i = 0;
    foreach ($pattern as $configuration) {
        $a = "A";
        
        if (implode($configuration) == 0) {
            $array[$i][] = ""; //entracte

            
        } else {
            foreach ($configuration as $character) {
                
                if ($character) {
                    $array[$i][] = $a;
                }
                $a++;
            }
        }
        $i++;
    }
    $pattern = $array;
    return $pattern;
}

function multistring(array $pattern) {


    //1A

    $i = 0;
    foreach ($pattern as $configuration) {
        $pattern[$i] = implode("-", $configuration);
        $i++;
    }
    $pattern = implode("/", $pattern);

    //print_r($pattern);
    return $pattern;
}

function multiclean(array $pattern, $characters = false) {


    //passe les id en clefs en valeur
    
    //enlève les personnages absents

    
    //TODO : deux conf successices identiques

    
    //TODO : entracte

    $array = array();
    foreach ($pattern as $kconfiguration => $configuration) {
        foreach ($configuration as $id => $character) {
            
            if ($character) {
                $array[$kconfiguration][] = $characters ? $characters[$id] : $id;
            }
        }
    }
    return $array;
}

function flatmat(array $pattern) {


    //A1
    $pattern = implode("<br/>", $pattern);
    return $pattern;
}

//arrangement

function flatgroup(array $pattern) {


    //A1
    $pattern = array_unique($pattern);
    $pattern = array_values($pattern);
    return $pattern; //A1

    
}

function flatorder(array $pattern) {


    //A1
    arsort($pattern);
    return $pattern; //A1

    
}

function flatremoveempty(array $pattern) {
    
    //A1 ou 1A selon qu'on veut supprimer les conf ou les personnages vides

    foreach ($pattern as $key => $row) {
        
        if ($row == 0){
            unset($pattern[$key]);
        }

    }
    return $pattern;
}
function flatremoverepeat(array $pattern) {
    
    //A1 ou 1A selon qu'on veut supprimer les conf ou les personnages qui se répète
    $i = 0;
    $array = array();
    foreach ($pattern as $key => $row) {

        if($i == 0){
            $array[] = $row;
            $i++;
            continue;
            }
        if ($row != $pattern[$i-1]) {
            $array[] = $row;
        }
        $i++;
    }
    return $array;
}
//compte
function flatcountconf(array $pattern){
    //1A
    //compte le nombre de configurations, sans les entractes
    $i = 0;
    foreach($pattern as $configuration){
        if($configuration != 0){$i++;}
        
    }
    return $i;
}
//test

function flatempty(array $pattern) {


    //A1 ou 1A selon qu'on teste les conf vides ou les pers absents
    
    //TODO : conf vide à traiter pour les entractes

    $true = false;
    foreach ($pattern as $row) {
        
        if ($row == 0) {
            $true = true;
        }
    }

    //loop+condition (ou condition seule ?)
    return $true; // si une ligne = 0

    
}

function flatrepeat(array $pattern) {


    //1A
    $i = 0;
    $true = false;
    foreach ($pattern as $row) {
        
        if (isset($pattern[$i + 1])) {
            
            if ($pattern[$i] == $pattern[$i + 1]) {
                $true = true;
            }
        }
        $i++;
    }

    //loop+condition
    return $true; // si deux conf successives sont identiques

    
}

function flatduplicate(array $pattern) {


    //A1
    $true = (count($pattern) != count(flatgroup($pattern))) ? true : false;
    return $true; //si deux pers ont les mêmes mouvements

    
}

//affichage

function print_pattern($pattern, $string = false) {

    $html = flatmat($pattern);
    $html.= "<br/>";
    
    if ($string) {
        $pattern = flatmulti($pattern);
        $pattern = multiflip($pattern);
        $pattern = intcode($pattern);
        $html.= multistring($pattern);
    }
    $html.= "<br/><br/>";
    return $html;
}

function print_patterns($patterns, $string = false) {

    $html = count($patterns) . " motifs<br/><br/>";
    foreach ($patterns as $pattern) {
        $html.= print_pattern($pattern, $string);
    }
    return $html;
}

//extraction

/*





 *multiflat (A1)
 *order
 *(group)
 *–flatbin //store
 *–flatmulti (A1)
 *multiflip (1A)
 *multistring //store
*/

//génération

/*





 *binflat (A1)
 *order
 *flatempty : exclure le motif si un personnage est toujoours absent
 *(flatduplicate : exclure le motif si on peut grouper deux personnages)
 *flatmulti (A1)
 *multiflip (1A)
 *multiflat (1A)
 *flatempty : exclure les conf vide
 *flatrepeat : exclure les conf qui se répétent
 *flatmulti (1A)
 *multistring //store avec le bin si a passé les tests
*/
?>