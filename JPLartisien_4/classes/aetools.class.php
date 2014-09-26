<?php
###########################################
### VERSION	1.01						###
### NOM		AeTools						###
### DATE	03/03/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AeTools                               */
/* Outils et utilitaires divers          */
/* ************************************* */
CLASS AeTools {
	private static $_Wordcut = false; ###Autorise le coupage de mots (si false, coupe à l'espace inférieur)


	#######################
	### Outils de texte ###
	#######################

	### Renvoie le texte $t réduit à $n lettres / Sans couper les mots
	### si $tre = true (par défaut), ajoute "..." à la suite du texte
	### Pour autoriser le coupage de mots, mettre $_Wordcut à "true"
	public static function resumtxt($t, $n, $tre=true) {
		$prohib=array(' ',',',';','.');
		if(strlen($t)>=$n) {
			$r1=substr($t, 0, $n);
			if(!self::$_Wordcut) while(substr($r1, -1)!=" " && strlen($r1)>0) $r1=substr($r1, 0, -1);
			if(strlen($r1)<1) $r1=substr($t, 0, $n);
			if(in_array(substr($r1, -1), $prohib)) $r1=substr($r1, 0, -1);
			if($tre) $r1=trim($r1)."...";
		} else $r1=$t;
		return $r1;
	}
	### Définit $_Wordcut à "on" (permet de coupage de mots)
	public static function wordcut_on() {
		self::$_Wordcut=true;
	}
	### Définit $_Wordcut à "off" (interdit le coupage de mots)
	public static function wordcut_off() {
		self::$_Wordcut=false;
	}
	### Inverse $_Wordcut ("on" <=> "off")
	public static function wordcut_toggle() {
		self::$_Wordcut=!self::$_Wordcut;
	}
	### Renvoie l'état de $_Wordcut
	public static function getWordcut() {
		return self::$_Wordcut;
	}
	### Nettoie un texte issu de données XML ou d'objet (suppression d'espaces en extrémités, mise au format chaîne)
	public static function xmlstr($t) {
		// return trim(strval($t));
		return trim((string) $t);
	}


	##########################
	### Outils de fichiers ###
	##########################

	### Renvoie la liste des fichiers du répertoire $path
	### $type = filte preg_match
	public static function ListeDir($path, $type=null) { //type = all ou extension
		$dir = dir($path);
		$nof = array('.', '..');
		$i=0;
		$ret = array();
		while (false !== ($fich = $dir->read())) {
			$r=null;
			if(!in_array($fich, $nof)) {
				if(is_string($type)) $r=preg_match("/".$type."$/i", $fich); else $r=true;
				if($r) {
					$ret[]=$fich;
				}
			}
		}
		if(sizeof($ret)<1) {unset($ret);$ret=false;} else sort($ret);
		return $ret;
	}

	public static function dumpdata($tableau, $titre = null, $haut = 100) {
		if($haut != "auto") $haut = $haut."px";
		echo("<pre style='width:90%;height:".$haut.";overflow:auto;border:1px solid #999;border-left:8px solid #999;'>");
		if($titre) echo("<div style='color:#999;font-weight:bolder;border-bottom:1px dotted #999;background-color:#DDD;'>".$titre." (dump)</div>");
		var_dump($tableau);
		echo("</pre>");
	}

	public static function printdata($tableau, $titre = null, $haut = 100) {
		echo("<pre style='width:90%;height:".$haut.";overflow:auto;border:1px solid #999;border-left:8px solid #999;'>");
		if($titre) echo("<div style='color:#999;font-weight:bolder;border-bottom:1px dotted #999;background-color:#DDD;'>".$titre." (print)</div>");
		print_r($tableau);
		echo("</pre>");
	}

}

?>
