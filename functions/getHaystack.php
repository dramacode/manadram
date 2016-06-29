<?php

function getHaystack($files, $n, $confidents, $group, $corpus, $all) {
    $dom = new DOMDocument();
    $haystack = array();
    foreach ($files as $file) {
        $fileName = basename($file, ".xml");
        $dom->load($file);
        $xp = new DOMXPath($dom);
        $xp->registerNamespace("tei", "http://www.tei-c.org/ns/1.0");
        $characters = ($confidents) ? $xp->evaluate("//tei:role[@statut!='confident'][@statut!='confidente'][@statut!='suivante'][@statut!='domestique'][@statut!='valet'][@statut!='suite'][@statut!='gouverneur'][@statut!='garde']/@xml:id|//tei:role[not(@statut)]/@xml:id") : $xp->evaluate("//tei:person[not(@corresp = preceding::tei:person/@corresp)]/@corresp");
        $array = array();
        foreach ($characters as $character) {
            $array[] = ($confidents) ? "#" . $character->textContent : $character->textContent;
        }

        //faire un truc pour sauter le fichier en cas d'erreur
        
        //print_r($array);

        $characters = $array;

        //echo "<br/>".$fileName;print_r($confidents);print_r($characters);
        $configurations = $xp->evaluate("//tei:listPerson");
        foreach ($configurations as $configuration) {
            $sceneId = $xp->evaluate("./ancestor::*[@type='scene']", $configuration);

            //print_r($sceneId);echo $fileName."<br/>";
            $sceneId = $sceneId->item(0)->getAttribute("xml:id");
            $actId = $xp->evaluate("./ancestor::*[@type='act']", $configuration); // suppose que toutes les scnes soient dans un acte

            $actId = $actId->item(0)->getAttribute("xml:id");

            //print_r($actId);echo $sceneId.$fileName."<br/>";
            
            //$sceneId = $configuration->parentNode->parentNode->getAttribute("xml:id");

            $scene = $xp->evaluate("./ancestor::*[@type='scene']", $configuration);

            //                         echo "<br/>";
            
            //echo $fileName;print_r($scene);continue;

            
            //              continue;

            
            //              //->getAttribute("xml:id");

            
            //echo "<br/>";

            
            //echo ($scene == "" or $scene == " " or !$scene)? "false".$fileName : $sceneId;

            
            //continue;

            $scene = ($scene->length > 0) ? $scene->item(0)->getAttribute("n") : "";
            $act = $xp->evaluate("./ancestor::*[@type='act']", $configuration);
            $act = ($act->length > 0) ? $act->item(0)->getAttribute("n") : "";

            //$acteId = $configuration->parentNode->parentNode->parentNode->getAttribute("xml:id");
            $persons = $array;

            //first conf
            $persons[0] = $xp->evaluate("tei:person/@corresp", $configuration);
            $array = array();
            foreach ($persons[0] as $person) {
                $array[] = $person->textContent;
            }
            $persons[0] = $array;
            //echo "<br/>".$fileName.$sceneId;print_r($array);
            //continue;
            //next conf, depending on the number of conf asked
            $i = 1;
            //echo $n."<br/>";
            //continue;
            //$next = $xp->evaluate("./following::tei:listPerson[" . $i . "]", $configuration);
            //echo "<br/>".$fileName.$sceneId;print_r($next);
            //
            $true = true;
            while ($i < $n) {
                $next = $xp->evaluate("./following::tei:listPerson[" . $i . "]", $configuration);
                
                if ($next->length == 0) {
                    $true = false;
                } else {
                    $next = $next->item(0);
                    $nextAct = $xp->evaluate("./ancestor::*[@type='act']", $next)->item(0)->getAttribute("xml:id");
                    
                    if ($nextAct != $actId and !$all) {
                        $true = false;
                    } else {
                        $persons[$i] = $xp->evaluate("./tei:person/@corresp", $next);
                        $array = array();
                        foreach ($persons[$i] as $person) {
                            $array[] = $person->textContent;
                        }
                        $persons[$i] = $array;
                        foreach ($characters as $character) {
                            $haystack[$fileName . "_" . $sceneId]["pattern"][$character][$i] = in_array($character, $persons[$i]) ? 1 : 0;
                        }
                    }
                }
                $i++;
            }
            if (!$true) {
                unset($haystack[$fileName . "_" . $sceneId]);
                continue;
            }
            
            foreach ($characters as $character) {
                $haystack[$fileName . "_" . $sceneId]["pattern"][$character][0] = in_array($character, $persons[0]) ? 1 : 0;
                ksort($haystack[$fileName . "_" . $sceneId]["pattern"][$character]);
            }

            $haystack[$fileName . "_" . $sceneId]["id"] = array(
                    "play" => $fileName,
                    "act" => $act,
                    "scene" => $scene,
                    "sceneId" => $sceneId
                );
            $haystack[$fileName . "_" . $sceneId]["id"] = array_merge($haystack[$fileName . "_" . $sceneId]["id"], biblio($fileName));
         
            unset($haystack[$fileName . "_" . $sceneId]["id"]["roles"]);
            //continue;
            //preview pattern
            $array = array();
            $array2 = array();
            foreach ($haystack[$fileName . "_" . $sceneId]["pattern"] as $key => $character) {
                $i = 0;
                foreach ($character as $configuration) {
                    
                    if (!isset($array[$i])) {
                        $array[$i] = "";
                        $array2[$i] = "";
                    }
                    
                    if ($configuration) {
                        $array[$i].= $corpus[$fileName]["roles"][str_replace("#", "", $key) ] . "-";
                        $array2[$i].=$key.",";
                    }
                    $i++;
                }
            }
            $cpp = implode(";", $array2);
            $cpp = trim(str_replace(",;", ";", $cpp) , ",");
            $string = implode("/", $array);
            $string = trim(str_replace("-/", "/", $string) , "-");
            $haystack[$fileName . "_" . $sceneId]["id"]["string"] = $string;
            $haystack[$fileName . "_" . $sceneId]["id"]["string-id"] = $cpp;

            //remove stuff
            foreach ($haystack[$fileName . "_" . $sceneId]["pattern"] as $key => $character) {
                
                if (!in_array(1, $character)) {
                    unset($haystack[$fileName . "_" . $sceneId]["pattern"][$key]);
                }

                //group characters
                
                if ($group) {
                    foreach ($haystack[$fileName . "_" . $sceneId]["pattern"] as $key2 => $character2) {
                        
                        if (($key != $key2) and ($character2 == $character)) {
                            unset($haystack[$fileName . "_" . $sceneId]["pattern"][$key]);
                        }
                    }
                }
            }


        }
    }

    //confident
    
    //group : unset char if same pattern

    
    //echo "<pre>";

    
    //

    
    //

    
    //print_r($haystack);

    return $haystack;
}
?>