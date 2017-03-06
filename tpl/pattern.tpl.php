
<div id="<?php echo $needle; ?>" class="pattern-results">
    <h3><?php echo $needle; ?></h3>
    <div id="tables-<?php echo $needle; ?>" class="tables-container">
        <?php include ("tpl/table_occurrences.tpl.php");
            foreach ($fields as $key => $field) {
                if ($key == "code") {
                    include ("tpl/table_code.tpl.php");
                } elseif (isset($field["type"])) {
                    include ("tpl/table_xpath.tpl.php");
                } else {
                    include ("tpl/table_generic.tpl.php");
                }
            }
        ?>
    </div>
    <?php include ("tpl/graph.tpl.php"); ?>
</div>
