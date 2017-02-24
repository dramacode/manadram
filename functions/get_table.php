<?php
function get_table($id){
    
    
    $file = "../tcp5/" . $id . ".xml";
    $xsl = new DOMDocument();
    $xsl->load("tpl/table.fr.xsl");
    $inputdom = new DomDocument();
    $inputdom->load($file);
    $proc = new XSLTProcessor();
    $xsl = $proc->importStylesheet($xsl);
    $code = basename($file, ".xml");
    $proc->setParameter("", "basename", $code);
    $newdom = $proc->transformToDoc($inputdom);
    return $newdom->SaveXML();
}


?>
