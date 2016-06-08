<?php
function biblio($play) {

    $dom = new DOMDocument();
    $dom->load("../tcp5/" . $play . ".xml");
    $xp = new DOMXPath($dom);
    $xp->registerNamespace("tei", "http://www.tei-c.org/ns/1.0");
    $author = $xp->evaluate("//tei:titleStmt/tei:author/tei:surname");
    $author = ($author->length > 0) ? $author->item(0)->textContent : "";
    $title = $xp->evaluate("//tei:titleStmt/tei:title[not(@type)]");
    $title = ($title->length > 0) ? $title->item(0)->textContent : "";
    $date = $xp->evaluate("//tei:creation/tei:date[@type='created']");
    $date = ($date->length > 0) ? substr($date->item(0)->getAttribute("when") , 0, 4) : "";
    $date = (int)$date;
    $lustrum = roundUpToAny($date);
    $lustrum = ($lustrum-4)."-". $lustrum;
    $genre = $xp->evaluate("//tei:keywords/tei:term[@type='genre']");
    $genre = ($genre->length > 0) ? $genre->item(0)->textContent : "";
    $roles = $xp->evaluate("//tei:person");
    $array = array();
    foreach($roles as $role){
        $role=str_replace("#", "", $role->getAttribute("corresp"));
        $name = $xp->evaluate("//tei:role[@xml:id='".$role."']");
        $name = ($name->length >0 )? $name->item(0)->textContent : "";
        $array[$role] = $name;
    }
    
    return array(
        "author" => $author,
        "title" => $title,
        "date" => $date,
        "lustrum" => $lustrum,
        "genre" => $genre,
        "play" => $play,
        "titleId" => normalize($title),
        "genreId" => normalize($genre),
        "authorId" => normalize($author),
        "roles" => $array
    );
}

?>