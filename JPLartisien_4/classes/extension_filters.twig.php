<?php
###########################################
### VERSION	1.00						###
### NOM		ExtFiltersTwig				###
### DATE	03/03/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/*
Descriptif des filtres TWIG :
dateFR				->	Transforme l'élément au format DATE de la BDD en date en français
extrait				->	Renvoie le texte $tx en le tronquant au niveau de l'espace situé avant le nombre de lettre $n + ajoute "..." si le texte est effectivement plus court
ucfirst				->	Met la première lettre en majuscule
encodeUTF8			->	Encode UTF-8
decodeUTF8			->	Décode UTF-8
nomPHP				->	Génère le nom du fichier PHP équivalent au libéllé du menu
cesuresOptimise		->	Optimisation des césures : évite les articles en bout de ligne, etc.
*/

$filter=array();

$filter[] = new Twig_SimpleFilter('dateFR', "dateFR");
$filter[] = new Twig_SimpleFilter('extrait', "extrait");
$filter[] = new Twig_SimpleFilter('ucfirst', "ucf");
$filter[] = new Twig_SimpleFilter('encodeUTF8', "encodeUTF8");
$filter[] = new Twig_SimpleFilter('decodeUTF8', "decodeUTF8");
$filter[] = new Twig_SimpleFilter('nomPHP', "nomPHP");
$filter[] = new Twig_SimpleFilter('cesuresOptimise', "cesuresOptimise");

foreach($filter as $filtre)
	$twig->addFilter($filtre);





/* ********* */
/* FONCTIONS */
/* ********* */

function dateFR($tx) {
	$mois = array("janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre");
	$t = explode("-",$tx);
	$m = $mois[intval($t[1])];
	$j = intval($t[2]);
    return $j." ".$m." ".$t[0];
}

function extrait($tx, $n) {
	$t1 = wordwrap($tx, $n, "|||", true);
	$t2 = explode("|||", $t1);
	$t = $t2[0];
	if($t != $tx) $t.="...";
	return $t;
}

function ucf($tx) {
	return ucfirst($tx);
}

function encodeUTF8($tx) {
	return utf8_encode($tx);
}

function decodeUTF8($tx) {
	return utf8_decode($tx);
}

function nomPHP($tx) {
	return urlencode(strtolower(str_replace(' ','_',$tx)).".php");
}

function cesuresOptimise($tx) {
	$in = array(" de ", " la ", " le ", " du ", " des ", " un ", " une ", " d' ", " ou ", " à ", " aux ", " ses ", " ces ", " et ");
	$out = array(" de&nbsp;", " la&nbsp;", " le&nbsp;", " du&nbsp;", " des&nbsp;", " un&nbsp;", " une&nbsp;", " d'&nbsp;", " ou&nbsp;", " à&nbsp;", " aux&nbsp;", " ses&nbsp;", " ces&nbsp;", " et&nbsp;");
	return str_replace($in, $out, $tx);
}

?>
