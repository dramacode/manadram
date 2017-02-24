<?php

function connect() {

    try {
        $bdd = new PDO('sqlite:manadram.sqlite');
    }
    catch(Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    return $bdd;
}

function insert($sql, $bdd, $data = false) {

    
    if ($data) {
        $req = $bdd->prepare($sql);
        $req->execute($data);
    } else {
        $req = $bdd->exec($sql);
    }
    
    if ($req === false) {
        echo 'ERREUR : ', print_r($bdd->errorInfo());
    } else {

        //echo 'Table créée';
        
    }
}

function select($sql, $bdd) {

    $req = $bdd->query($sql);
    $req = $req->fetch(PDO::FETCH_ASSOC);
    return $req;
}

function mselect($sql, $bdd) {

    $req = $bdd->query($sql);
    $req = $req->fetchAll(PDO::FETCH_ASSOC);
    return $req;
}
?>