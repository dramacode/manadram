<?php
/**
* Idée de contrat de classe pour un patron Manadram.
* Pour moi c’est comme une prise de notes sur notre conversation.
* Je n’ai pas d’utilisation, mais j’aurais compris des choses.
* Il y a presque toutes les notions de la construction objet :
* champs, méthodes, constructeurs, visibilité… (à part l’héritage).
* Il n’y a pas de code qui marche, juste une idée de construction.
* Je t’ai glissé quelques bonnes habitudes qu’on finit par apprendre
* en lisant le code de meilleurs que soi (en général ça me vient du code source de Java).
* Cet objet est transitoire, le temps de parser une pièce.
* Il permet de calculer une clé de configuration unique,
* comme une sorte de hashCode().
* Cette clé permet la comparaison des patrons,
* nombre qui peut être stocké dans une base de données.
*/

// Idée d’utilisation en pseudo code non testé
// Ce préalable donne l’idée des méthodes pratiques que l‘on veut dans son objet
$patStack = array();
$patmax = 3;
foreach ( $act as $conf ) {
 // avant d’empiler un nouveau patron on regarde le travail à faire dans la pile
 $newStack; // je ne sais pas si c'est performant de recopier la stack à chaque fois
 foreach ( $patStack as $pat ) {
   $pat->addConf( $conf['names']);
   // ce patron est plein, on le sort en base
   if ( $pat->size() == $patmax ) $base->storePattern( $pat->confid(), $pat->size(), $pat->code() );
   // ce patron n'est pas plein, on le rempile
   else $newStack[] = $pat;
 }
 $patStack = $newStack;
 $pat = new Pattern( $conf['id'], $patmax);
 $pat->addConf( $conf['names']);
 $patStack[] = $pat;
}
// ici on dépile les patrons qui restent dans la pile (les fins d’acte)
foreach ( $patStack as $pat ) {
 $base->storePattern( $pat->confid(), $pat->size(), $pat->code() );
}



class Pattern {
 /** Identifiant de la configuration de départ, non modifiable après création de l’objet */
 private $confid;
 /** Nombre maximal de configurations du patron, en comptant celui au départ, minimum : 2,
  * non modifiable après création de l’objet
  */
 private $max;
 /** Taille du patron en nombre de configurations, incrémenté à chaque ajout, non modifiable de l’extérieur */
 private $size;
 /** Le code à calculer */
 private $code = -1;
 /** Une petite mémoire  */
 private $lastSize;
 /** Pile de confs ajoutée  */
 private $conflist;
 /** Distribution locale à la configuration qui fixera l’ordre des personnages dans ce patron */
 private $dist;

 /**
  * Constructeur, prend l’identifiant de configuration de départ, et le maximum de conf autorisées
  */
 public function __construct( $confid, $max ) {
   $this->confid = $confid;
   $this->max = $max;
 }
 /**
  * Excité au moment de parser une pièce, va juste stocker une liste de noms,
  * on traite quand le patron a ses données.
  */
 public function addConf( $conf) {
   // ici on peut lancer une exception, ou silencieusement ne rien faire, pour éviter
   // un test dans la boucle d’acte
   if ( $this->size >= $this->max ) throw new Exception('Patron limité à $max');
   $this->conflist[] = $conf;
   $this->size++;
 }
 /**
  * C’est la poignée qui va démarrer la machine, demandé au moment du stockage du patron.
  * Tu es dans ta logique métier, tu connais mieux ton boulot que moi,
  * je ne serais ici qu’indicatif.
  */
 public function code() {
   // le calcul ici est le plus couteux alors on regarde si on peut mettre en cache
   // comme rien n'interdit encore de demander le code avant que le patron soit plein
   // vérifier que le dernier calcul de la clé était sur la même taille de patron
   if ( $this->code != -1 && $this->size == $this->lastSize ) return $this->code;
   // fixer la distribution locale aux configuration qui donnera l’ordre de référence
   // des rôles (et des groupes ?) pour le patron
   $this->dist();
   // construire la matrice où les lignes sont des configurations
   $matconf = array();
   foreach ( $this->conflist as $conf) {
     // on réplique la distribution locale pour allouer les drapeaux selon la conf
     $lineconf = $this->dist;
     foreach ( $conf as $role ) $lineconf[$role] = 1;
     // on garder l'ordre mais on dégage les clés
     $matconf[] = array_values( $lineconf );
   }
   // transposer la matrice pour que les lignes deviennent des rôles
   // appel à une fonction statique de cette classe
   $matrole = self::transpose( $matconf );
   // transformer la matrice par rôle en tableau d’int
   $roleint = array();
   foreach ( $matrole as $bin ) $roleint[] = bindec( implode( $bin ) );
   // trier par rôle, le plus présent en premier, pour faire une moins grosse clé plate
   // c’est cette ligne qui devrait normalement dédoublonner les confs qui se ressemblent
   rsort( $roleint);
   // aplatir le tableau d'int en un nombre int
   $this->code = $this->flat( $roleint );
   $this->lastSize = $this->size;
   return $this->code;
 }
 /**
  * Aplatir une série de int en une clé int unique
  * pète différemment selon les processeurs 32 bits ou 64 bits
  * http://php.net/manual/fr/language.types.integer.php
  */
 function flat( $intarray ) {
   $code = 0; // code int à forger
   // a priori chaque $int est ici entre 0? (présents mais silencieux) ou 1, et 2^$max-1
   // c’est-à-dire $max bits. Avec 3 confs, ça fait donc [000-111], [0-7]
   $coef = pow(2, $this->max); // le nombre binaire maximal d'un int + 1
   $mult = 1; // un multiplicateur pour décaler la position du int à ajouter à la clé
   foreach ( $intarray as $int ) {
     $code += $int*$mult;
     $mult *= $coef;
   }
   return $code;
 }
 /**
  * Google est plus fort que moi
  * http://stackoverflow.com/a/3423692
  * array_map avec une fonction null
  * http://php.net/manual/en/function.array-map.php#example-5593
  * ... le "splat" operator qui découpe le tableau en ligne, pas trouvé de doc officielle

  * Fonction statique, qui ne se réfère à aucun objet avec des données à lui
  */
 static function transpose( $array ) {
   return array_map(null, ...$array);
 }
 /**
  * Ramasse les rôles des configurations pour en établir une liste canonique
  * dans un ordre fixe qui servira de référence pour établir
  * la clé par conf.
  */
 private function dist() {
   // déjà fait, on redonne;
   if ( $this->dist ) return $this->dist;
   foreach ( $this->conflist as $conf) {
     foreach ( $conf as $role ) $this->dist[$role] = 0;
   }
   ksort( $this->dist );
 }

 /** Getter de l’identifiant de configuration de départ */
 public function confid() { return $this->confid; }
 /** Getter de l’identifiant de la taille finale */
 public function size() { return $this->size; }

}

?>w