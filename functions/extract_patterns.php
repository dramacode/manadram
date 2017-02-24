<?php

function extract_patterns($xp, $length, $confidents, $group) {


    //prend le xp d'un fichier, la longueur des motifs ˆà extraire (0 pour extraire un seul motif global), les options confidents et group
    
    //renvoie un array [scene_id] = array(int_dec, int_bin, str_code, str_id, str_name)

    $characters = ($confidents) ? $xp->evaluate("//tei:role[@statut!='confident'][@statut!='confidente'][@statut!='suivante'][@statut!='domestique'][@statut!='valet'][@statut!='suite'][@statut!='gouverneur'][@statut!='garde']|//tei:role[not(@statut)]") : $xp->evaluate("//tei:person[not(@corresp = preceding::tei:person/@corresp)]");

    //l'option confident suppose que tous les id sont dfinis dans la castList
    
    //liste des id des personnages

    $array = array();
    foreach ($characters as $character) {
        
        if ($confidents) {
            $id = $character->getAttribute("xml:id");
            $form = $character->textContent;
        } else {
            $id = str_replace("#", "", $character->getAttribute("corresp"));
            $form = $xp->evaluate("//tei:role[@xml:id='" . $id . "']");
            $form = $form->length > 0 ? $form->item(0)->textContent : "";
        }
        $form = $form ? $form : $id;
        $array[$id] = trim($form);
    }
    $characters = $array;

    //faire un truc pour sauter le fichier en cas d'erreur
    $patterns = array();
    $configurations = $xp->evaluate("//tei:listPerson");
    $all = $length ? false : true;
    $length = $length ? $length : $configurations->length;
    $k = 0; //pattern

    while ($k < $configurations->length) {
        $current_configuration = $first_configuration = $configurations->item($k); //conf de départ du motif

        $scene_id = $xp->evaluate("./ancestor::*[@type='scene']", $first_configuration);
        $scene_id = $scene_id->item(0)->getAttribute("xml:id"); //scene_id (renvoie toujours à un id existant) pourra différer de $configuration_id (-1 pour les débuts d'acte)

        $first_act = $xp->evaluate("./ancestor::*[@type='act']", $first_configuration); // suppose que toutes les scènes soient dans un acte

        $current_act_id = $act_id = $first_act->item(0)->getAttribute("xml:id");
        $scene_n = $xp->evaluate("./ancestor::*[@type='scene']", $first_configuration);
        $scene_n = ($scene_n->length > 0) ? $scene_n->item(0)->getAttribute("n") : "";
        $act_n = $xp->evaluate("./ancestor::*[@type='act']", $first_configuration);
        $act_n = ($act_n->length > 0) ? $act_n->item(0)->getAttribute("n") : "";
        $pattern = array();
        $i = 1; //conf

        $j = 0; //pattern element (conf+entractes)
        while ($i <= $length) {
            $entract_before = $entract_after = false;
            //on remplit pattern
            
            //previous conf

            $previous_configuration = $xp->evaluate("./preceding::tei:listPerson[1]", $current_configuration);
            
            if (($previous_configuration->length == 0)) {
                $entract_before = true;
            } else {
                $previous_configuration = $previous_configuration->item(0);
                $previous_act_id = $xp->evaluate("./ancestor::*[@type='act']", $previous_configuration)->item(0)->getAttribute("xml:id");
                
                if (($previous_act_id != $current_act_id)) {
                    $entract_before = true;
                }
            }
            
            if ($entract_before) {
                foreach ($characters as $character_id => $character) {
                    $pattern[$character_id][$j] = 0;
                }
                $j++;
            }

            //current conf
            foreach ($characters as $character_id => $character) {
                $pattern[$character_id][$j] = ($xp->evaluate("tei:person[@corresp='#" . $character_id . "']", $current_configuration)->length > 0) ? 1 : 0;
            }
            $j++;

            //next conf
            $next_configuration = $xp->evaluate("./following::tei:listPerson[1]", $current_configuration);
            
            if ($next_configuration->length == 0) {
                $entract_after = true;
                //if($i<$length-1){
                //    unset($pattern);
                //}
                //break;
            } else {
                $next_configuration = $next_configuration->item(0);
                $next_act_id = $xp->evaluate("./ancestor::*[@type='act']", $next_configuration)->item(0)->getAttribute("xml:id");
                $current_configuration = $next_configuration; //pour le tour suivant

                
                if (($next_act_id != $current_act_id)) {
                    $entract_after = true;
                    $current_act_id = $next_act_id;//pour le tour suivant
                }
            }
                    echo $scene_id;

            if ($entract_after and $i == $length) {
                foreach ($characters as $character_id => $character) {
                    $pattern[$character_id][$j] = 0;
                }
                $j++;
            }
            $i++;

        }
        $k++;
        echo "<pre>";print_r($pattern);
        if (!isset($pattern)) {
            continue;
        }

        //pattern est rempli, il est valide, on le transforme dans les diffrents formats
        
        //on l'inscrit dans patterns

        
        /*

        
        
        
        
        
        
        
        
        
            
            
            
            
            
             *multiflat (A1)
             *order
             *(group)
             *Ğflatbin //store
             *Ğflatmulti (A1)
             *multiflip (1A)
             *multistring //store
        */

        //[id][conf]value
        $pattern = multiflat($pattern);
        $pattern = flatremoveempty($pattern); //supprimer les personnages vides (comme je boucle sur tous les personnages)

        $id_pattern = $pattern = flatorder($pattern);
        $pattern = $group ? flatgroup($pattern) : $pattern; //à partir de là, je perds les identifiants de personnages dans $pattern : [n][conf]value

        $patterns[$scene_id]["int_bin"] = flatbin($pattern);
        $patterns[$scene_id]["int_dec"] = bindec(flatbin($pattern));
        $pattern = flatmulti($pattern);
        $pattern = multiflip($pattern); //à partir de là, l'array a la structure [conf][n]value

        
        //remove equal successive

        $pattern = multiflat($pattern);
        
        if (flatrepeat($pattern)) { //

            unset($patterns[$scene_id]);
            continue;
        }
        $pattern = flatmulti($pattern);
        $pattern = intcode($pattern);
        $patterns[$scene_id]["str_code"] = multistring($pattern);

        //id_pattern : ne pas grouper ; inutile de remove equal successive conf puisqu'on a déjà continue si c'est le cas ;
        $id_pattern = flatmulti($id_pattern);
        $id_pattern = multiflip($id_pattern);
        $patterns[$scene_id]["str_id"] = multistring(intid($id_pattern));
        $patterns[$scene_id]["str_name"] = multistring(intid($id_pattern, $characters));
        $patterns[$scene_id]["act_n"] = $act_n;
        $patterns[$scene_id]["scene_n"] = $scene_n;
        $patterns[$scene_id]["scene_id"] = $scene_id;
        $patterns[$scene_id]["l"] = $length;
        
        if ($all) {
            break; //on n'extrait pas les motifs suivants, uniquement un motif global à partir de la première scène

            
        }
    }
    //echo "<pre>";print_r($patterns);
    return $patterns;
}
?>