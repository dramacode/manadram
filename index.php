<?php

//error_reporting(0);
require_once ("functions/functions.php");
require_once ("functions/pattern.php");
require_once ("functions/patab.php");
require_once ("functions/get_corpus.php");
require_once ("functions/get_needle.php");
require_once ("functions/search.php");
require_once ("functions/db.php");
require_once ("functions/statify.php");
require_once ("functions/form.php");
require_once ("functions/plays.php");
require_once ("functions/json.php");
require_once ("functions/get_filters.php");
echo '<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
        <script type="text/javascript" src="js/jquery.hoverIntent.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/tipsy/src/javascripts/jquery.tipsy.js"></script>
        <script type="text/javascript" src="js/fancybox/source/jquery.fancybox.js"></script>
        <script type="text/javascript" src="TableFilter/dist/tablefilter/tablefilter.js"></script>
        <script type="text/javascript" src="js/excelexport/dist/jquery.battatech.excelexport.min.js"></script>
        <script type="text/javascript" src="js/dygraph/dygraph-combined.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="js/results.js"></script>
<script type="text/javascript" src="js/export.js"></script>

       <link rel="stylesheet" href="js/fancybox/source/jquery.fancybox.css" type="text/css" />
        <link rel="stylesheet" href="js/tipsy/src/stylesheets/tipsy.css" type="text/css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.css" type="text/css" />
      <link rel="stylesheet" href="js/jquery-ui-1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/form.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
     <title>Moteur d\'analyse dramaturgique</title>
</head>

<body>';
    $bdd = connect();

$lang = (isset($_GET["lang"]) and $_GET["lang"] == "en") ? "en" : "fr";
$corpus = get_corpus();
include ("lang/" . $lang . ".php");
include ("tpl/header.tpl.php");
include ("tpl/corpus.html");
echo '<script type="text/javascript" src="js/main.js"> </script>';
form($bdd);


//include ("tpl/form.tpl.php");

if (isset($_GET["post"])) {
    echo '<div class="res">';
    doPost($bdd);
    echo '</div>';
}
include ("tpl/footer.tpl.php");
echo '</body></html>';

function doPost($bdd) {

    $a = microtime(true);
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
    $needle = get_needle();
    $length = $needle["length"];
    $needle = $needle["needle"];
    $group = (isset($_GET["group"])) ? 1 : 0;
    $confidents = (isset($_GET["ignore_confident"])) ? 1 : 0;
    //filtres :  (xpath)
    //created ≠ lustrum (je ne peux pas prendre en compte created parce que les tableaux s'affichent par lustres) : le dire dans la documentation
    //liste des valeurs de champs : si j'ai un trou dans lustrum dans mon corpus (dans statify ?). Pour l'instant ça va, mais avec l'Allemagne ?
    //faut-il virer A//B des stats A/B ?
    //design
    //index pour les filtres => Fred ?
    //generate
    //si field[str_code] => $needles : length ? garder les options et les filtres ; multi pattern : résultats groupés / tous les résultats (avec un comparateur : chrono, non chrono, par auteur, genre ?) / mixte
    //comparateur : si count patterns > 1, tableau récap motif/occ/propor + graphe camembert, puis, pour chaque motif, graphe et tableaux
    //résultats groupé : si count patterns>1, agréger les résultats (comment statify ?)
    
    if($_GET["filters"]["from"]){$filters["from"] =  "created >= '".$_GET["filters"]["from"]."'";}
    if($_GET["filters"]["to"]){$filters["to"] =  "created <= '".$_GET["filters"]["to"]."'";}//<= ?
    foreach($fields as $key=>$field){
        if(($key != "author") AND ($key != "genre") AND ($key != "code")){continue;}
        if(isset($_GET["filters"][$key])){
            $array = array();
            foreach($_GET["filters"][$key] as $filter){
                $array[] = $key." = '".$filter."'";
            }
            $filters[$key] =  "(";
            $filters[$key] .= implode(" OR ", $array);
            $filters[$key] .=  ")";
        }
    }
    $filter_play = isset($filters) ? " WHERE ".implode(" AND ", $filters) : "";
    $filter = isset($filters) ? " AND play_id IN (SELECT id FROM play ".$filter_play.")" : "";
    $results = search($needle, $length, $confidents, $group, $bdd, $filter);
    $results_n = count($results);
    $sql = "SELECT SUM(value) FROM stats WHERE l = " . $length . " AND c = " . $confidents . " AND g = " . $group.$filter;
    $result = select($sql, $bdd);
    $global_n = reset($result);
    $plays = plays($bdd, $filter_play);
    if(!$plays){echo "Aucune pièce ne remplit ces critères.";return;}
    $proportion = round(($results_n / $global_n) * 100, 2);
    //echo "<pre>";print_r($results);
    foreach ($fields as $key => $field) {
        $xpath = isset($field["type"]) ? $field : false;
        $table_results[$key] = statify($results, $key, $length, $group, $confidents, $xpath, $plays, $bdd, $filter, $filter_play);
    }
    $b = microtime(true);
    $time = round($b - $a, 2);
    //echo "<pre>";
    //print_r($table_results);
    $json = json($table_results["lustrum"]);
    
    //csv
    include ("tpl/patab.tpl.ggl.php");
    echo "<div id='tables'>";

    //occurrences
    include ("tpl/occurrences.tpl.php");

    //tables
    foreach ($fields as $key => $field) {
        
        if ($key == "code") {
            include ("tpl/table_code.tpl.php");
        } elseif (isset($field["type"])) {
            include ("tpl/table_xpath.tpl.php");
        } else {
            include ("tpl/table.tpl.php");
        }
    }
    echo "</div>";


    
}
?>

