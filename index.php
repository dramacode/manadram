<?php

error_reporting(0);
require_once ("functions/functions.php");
require_once ("functions/pattern.php");
require_once ("functions/patab.php");
require_once ("functions/get_corpus.php");
require_once ("functions/get_needle.php");
require_once ("functions/search.php");
require_once ("functions/db.php");
require_once ("functions/statify.php");
require_once ("functions/plays.php");
require_once ("functions/json.php");
require_once ("functions/get_filters.php");
require_once ("functions/doPost.php");
$bdd = connect();
$lang = (isset($_GET["lang"]) and $_GET["lang"] == "en") ? "en" : "fr";
include ("lang/" . $lang . ".php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="js/jquery.hoverIntent.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/tipsy/src/javascripts/jquery.tipsy.js"></script>
    <script type="text/javascript" src="js/fancybox/source/jquery.fancybox.js"></script>
    <script type="text/javascript" src="js/TableFilter/dist/tablefilter/tablefilter.js"></script>
    <script type="text/javascript" src="js/export.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="js/main.js"></script>    
    <link rel="stylesheet" href="js/fancybox/source/jquery.fancybox.css" type="text/css" />
    <link rel="stylesheet" href="js/tipsy/src/stylesheets/tipsy.css" type="text/css" />
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.css" type="text/css" />
    <link rel="stylesheet" href="js/jquery-ui-1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <title>Moteur d'analyse dramaturgique</title>
</head>
<body>
    <script type="text/javascript">document.body.style.display = "none";</script>    
    <div class="res">
    <?php
        //filtres :  (xpath)
        
        //created ≠ lustrum (je ne peux pas prendre en compte created parce que les tableaux s'affichent par lustres) : le dire dans la documentation
        
        //liste des valeurs de champs : si j'ai un trou dans lustrum dans mon corpus (dans statify ?). Pour l'instant ça va, mais avec l'Allemagne ?
        
        //faut-il virer A//B des stats A/B ?
        
        //index pour les filtres => Fred ?
        
        //comparateur : graphe camembert, temps d'exécution, lier les tableaux (pb de l'ancre : fait planter le graph)
        
        //résultats groupé : si count patterns>1, agréger les résultats (comment statify ?)
        //lien sur le motif et rappel (dans la même page ou une page différente)
        //nombre total
        //pas de résultat
        //colonnes genre (dans le tableau)
        //colonne lustre (dans le tableau)
        //dans ce cas, il resterait occurrences, auteur, xpath
        //lignes genres (dans les graphes)
        //un graphique pour tous les motifs (mais dans ce cas je ne peux pas faire une ligne par genre)
        //Quelle utilité ?
        include ("tpl/header.tpl.php");
        include ("html/corpus.html");
        include ("tpl/form.tpl.php");
        if (isset($_GET["post"])) {
            $fields = array(
                "code" => array(
                    "title" => "Résultats par pièce",
                    "head" => "Titre"
                ) ,
                "author" => array(
                    "title" => "Résultats par auteur",
                    "head" => "Auteur"
                ) ,
                "genre" => array(
                    "title" => "Résultats par genre",
                    "head" => "Genre"
                ) ,
                "lustrum" => array(
                    "title" => "Résultats par année",
                    "head" => "Année"
                ) ,
            );
            foreach ($_GET["xpath"] as $key => $xpath) {
                
                if (!$xpath) {
                    continue;
                }
                $fields[$key] = array(
                    "title" => $xpath,
                    "type" => "xpath",
                );
            }
            $group = (isset($_GET["group"])) ? 1 : 0;
            $confidents = (isset($_GET["ignore_confident"])) ? 1 : 0;
            $filters = array();
            if ($_GET["filters"]["from"]) {
                 $filters["from"] = "created >= '" . $_GET["filters"]["from"] . "'";
            }
            if ($_GET["filters"]["to"]) {
                 $filters["to"] = "created <= '" . $_GET["filters"]["to"] . "'";
            }         
            foreach ($fields as $key => $field) {                 
                 if (($key != "author") AND ($key != "genre") AND ($key != "code")) {
                     continue;
                 }                 
                 if (isset($_GET["filters"][$key])) {
                     if(!($_GET["filters"][$key][0])){continue;}
                     $array = array();
                     foreach ($_GET["filters"][$key] as $filter) {                         
                         $array[] = $key . " = '" . $filter . "'";
                     }
                     $filters[$key] = "(";
                     $filters[$key].= implode(" OR ", $array);
                     $filters[$key].= ")";
                 }
            }
            $filter_play = ($filters) ? " WHERE " . implode(" AND ", $filters) : ""; 
            $filter = ($filters) ? " AND play_id IN (SELECT id FROM play " . $filter_play . ")" : "";
            $all_results = doPost($bdd, $fields, $group, $confidents, $filter, $filter_play);
            include("tpl/table_summary.tpl.php");
            foreach($all_results as $needle=>$all_result){
                $needletr = strtr($needle, "/", "_");
                $results = $all_result["results"];
                $results_n = $all_result["count"];
                $proportion = $all_result["proportion"];
                $length = $all_result["length"];
                $plays = $all_result["plays"];
                foreach ($fields as $key => $field) {
                    $xpath = isset($field["type"]) ? $field : false;
                    $table_results[$key] = statify($results, $key, $length, $group, $confidents, $xpath, $plays, $bdd, $filter, $filter_play);
                }
                $json = json($table_results["lustrum"]);                
                include("tpl/pattern.tpl.php");
            }
        }
    ?>
    </div>
</body>
</html>

