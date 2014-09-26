<?php
###########################################
### VERSION	1.00						###
### NOM		ExtFonctionsTwig			###
### DATE	03/03/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* 
Descriptif des fonctions TWIG :
scpmembretri		->	Renvoie l'ID du membre courant, du précédent et du suivant
*/
 
$funct=array();
$decal=-1;
$tab="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

$funct[] = new Twig_SimpleFunction('scpmembretri', "scpmembretri");
$funct[] = new Twig_SimpleFunction('arrayprint', "arrayprint");
$funct[] = new Twig_SimpleFunction('lignestylecss', "lignestylecss");

foreach($funct as $fonct)
	$twig->addFunction($fonct);


/* ********* */
/* FONCTIONS */
/* ********* */

function scpmembretri($liste, $membre) {
	foreach($liste as $rang => $m) {
		if($m['membre_ID'] == $membre['membre_ID']) {
			$r['IDprec']=$liste[$rang-1]['membre_ID'];
			$r['IDcourant']=$liste[$rang]['membre_ID'];
			$r['IDsuiv']=$liste[$rang+1]['membre_ID'];
			break 1;
		}
	}
	return $r;
}

// Affiche le contenu d'un tableau (array)
function arrayprint($arr, $nomt = null) {
	$a = fe($arr, $nomt);
	return $a;
}

// Crée un texte de style du type style="..." d'après un tableau associatif
function lignestylecss($arr) {
	$styles = array("color","font-weight");
	$top = $linestyle = $back = "";
	foreach($arr as $nom => $style) {
		if(in_array($nom, $styles)) {
			$top = ' style="';$back = '"';
			$linestyle .= $nom.":".$style.";";
		}
	}
	$a = $top.$linestyle.$back;
	return $a;
}

function fe($arr, $nomt = null) {
	global $decal, $tab;
	if($nomt != null) {
		$arr2[$nomt]=$arr;$arr=$arr2;
	}
	$decal++;
	$tabul="";
	for($i=0;$i<$decal;$i++) $tabul.=$tab;
	if(is_array($arr)) foreach($arr as $nom => $val) {
		if(is_array($val) && sizeof($val)>0) {
			if($nomt != null) $html.="<h3 style='color:white;background-color:#449;margin-top:6px;'>".$tabul."&nbsp;".$nom."&nbsp;</h3>";
			else $html.="<h3 style=''>".$tabul."[".$nom."]</h3>";
			$html.=fe($val);
		} else if(is_object($val)) {
			// $html.="<p>&nbsp;<span style='font-size:9px;font-style:italic;'>(Objet twig)</span></p>";
		} else {
			$v=gettype($val);
			if($val === true) $val='true';
			if($val === false) $val='false';
			if($nomt != null) $html.="<h3 style='color:white;background-color:#449;margin-top:6px;'>".$tabul."&nbsp;".$nom."&nbsp;</h3>";
			else $html.="<h3 style='float:left;'>".$tabul."[".$nom."]</h3>";
			$html.="<p>&nbsp;".$val." <span style='font-size:9px;font-style:italic;'>(".$v.")</span></p>";
		}
	} else $html="<p>Aucune valeur</p>";
	$decal--;
	return $html;
}

?>
