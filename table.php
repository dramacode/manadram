    <?php
    require_once ("functions/db.php");
    $bdd = connect();
    $sql = "SELECT author, title, created, html FROM play WHERE code = '".$_GET["play"]."'";
    $html = select($sql, $bdd);
    include("tpl/table.tpl.php");
    ?>