<?php
error_reporting(0);
require_once ("functions/functions.php");
require_once ("functions/getNeedle.php");
require_once ("functions/searchPattern.php");
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
    <script type="text/javascript" src="js/main.js"> </script>
    <script type="text/javascript" src="js/results.js"></script>
<script type="text/javascript" src="js/export.js"></script>

       <link rel="stylesheet" href="js/fancybox/source/jquery.fancybox.css" type="text/css" />
        <link rel="stylesheet" href="js/tipsy/src/stylesheets/tipsy.css" type="text/css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.css" type="text/css" />
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/form.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
     <title>Moteur d\'analyse dramaturgique</title>
</head>

<body>';
include ("tpl/header.tpl.php");

//$files est passé à doPost et à form.tpl

//$needle = getNeedle();


//$n = count(array_shift(array_values($needle)));


//echo '<div style="display: none">	<div id="results" style="width:1000px;height:800px;overflow:auto;">';


if (isset($_POST["post"])) {

    //echo "<pre>";print_r($_POST);
    doPost($files, $corpus, $fields);

    //echo $b;
    
}
    if (isset($_GET["author"]) and $_GET["author"] == "corneillep") {
        include ("corneillep/corpus.php");
    } else {
        include ("data/corpus.php");
    }
//echo '</div></div>';
include ("tpl/corpus.tpl.php");
include ("tpl/form.tpl.php");
echo '</body></html>';

function doPost($files, $corpus, $fields) {

    $a = microtime(true);

    //echo "<pre>";print_r($haystack);
    $dfields = array(
        "author" => "Auteur",
        "play" => "Titre",
        "lustrum" => "Année",
        "genre" => "Genre"
    );
    foreach ($_POST["xpath"] as $key => $xpath) {
        
        if (!$xpath) {
            continue;
        }
        $fields[$key] = $xpath;
    }
    $needle = getNeedle();
    $n = count(array_shift(array_values($needle)));
    $group = (isset($_POST["group"])) ? true : false;
    $confidents = (isset($_POST["ignore_confident"])) ? true : false;
    $options = (!$confidents and !$group) ? "default" : "";
    $options.= $confidents ? "C" : "";
    $options.= $group ? "G" : "";
    $folder = (isset($_GET["author"]) and $_GET["author"] == "corneillep") ? "corneillep" : "data";
    $haystack = array();
    
    if (isset($_GET["author"]) and $_GET["author"] == "corneillep") {
        include ("corneillep/corpus.php");
        include ("corneillep/fields.php");
        include ("corneillep/haystack" . $n . $options .  ".php");
        $haystack = $haystack[$n][$options];
    } else {
        include ("data/corpus.php");
        include ("data/fields.php");
        include ("data/haystack" . $n . $options . ".php");
        $haystack = $haystack[$n][$options];
    }
    $searchResults = searchPattern($needle, $haystack, $dfields, $corpus, $fields);
    $b = microtime(true);
    $time = round($b - $a, 2);
    $patab = $searchResults["patab"];
    echo '<div class="res">';
    $occurrences = $searchResults["occurrences"];
    $csv = $searchResults["csv"];
    $tables = $searchResults["tables"];
    $results = $searchResults["results"];
    include ("tpl/patab.tpl.php");

    //include ("tpl/occurrences.tpl.php");
    include ("tpl/csv.tpl.php");
    include ("tpl/tables.tpl.php");

    //include ("tpl/results.tpl.php");
    echo '</div>';
}
?>

