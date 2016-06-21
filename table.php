<?php
error_reporting(0);
$lang = (isset($_GET["lang"]) and $_GET["lang"] == "en")? "en" : "fr";
include ("lang/".$lang.".php");
include("data/corpus.php");
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

    <script src="js/breaks.js" type="text/javascript"> </script>
    <link rel="stylesheet" type="text/css" href="css/form.css">
        <link rel="stylesheet" href="js/fancybox/source/jquery.fancybox.css" type="text/css" />
        <link rel="stylesheet" href="js/tipsy/src/stylesheets/tipsy.css" type="text/css" />
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
        <link rel="icon" type="image/x-icon" href="img/favicon.ico">

    <title>'.$corpus[$_GET["play"]]["title"].'</title>
</head>

<body>';
if (isset($_GET["play"])) {
    doGet($lang);
}
include("tpl/table.tpl.php");
echo '</body></html>';

function doGet($lang) {
    $file = "../tcp5/" . $_GET["play"] . ".xml";
    $xsl = new DOMDocument();
    $xsl->load("tpl/table.".$lang.".xsl");
    $inputdom = new DomDocument();
    $inputdom->load($file);
    $proc = new XSLTProcessor();
    $xsl = $proc->importStylesheet($xsl);
    $code = basename($file, ".xml");
    $proc->setParameter("", "basename", $code);
    $newdom = $proc->transformToDoc($inputdom);
    echo $newdom->SaveXML();
}

?>