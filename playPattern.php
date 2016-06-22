<?php
set_time_limit(20000);
error_reporting(E_ALL);
foreach (glob("functions/*.php") as $function) {
    require_once ("functions/" . basename($function));
}
$globs = explode("\n", trim(file_get_contents("corpus.txt")));
$files = array();
foreach ($globs as $glob) {
    $files = array_merge(glob($glob) , $files);
};
$mode = array(
    true,
    true
);
foreach ($files as $file) {
    $files2 = array($file);
    $corpus = array();
    $corpus[basename($file, ".xml") ] = biblio(basename($file, ".xml"));
    //$i = nbdeconf;
    //$files ???
    $haystack = getHaystak($files2, $i, $mode[0], $mode[1], $corpus);
    file_put_contents("plays/" . basename($file, ".xml") . ".php", '<?php $haystack = ' . var_export($haystack, true) . '; ?>');
}

//modifier getHaystack pour avoir le motif avec la liste des identifiants
//aller au delà de l'acte

?>