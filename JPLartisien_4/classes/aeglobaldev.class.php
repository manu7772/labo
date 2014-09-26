<?php
###########################################
### VERSION	1.02						###
### NOM		AeGlobalDev					###
### DATE	10/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AeGlobalDev					         */
/* ************************************* */
CLASS AeGlobalDev {

	public static $Valsp = array('off','on');	// Modes disponibles

	public static function init() {
		// $_SESSION[TMPDATA]["DEV"]=array();
		$_SESSION[TMPDATA]["DEV"]["initTime"] = microtime(true);
		$dev = strtolower(AeQuickXML::ValFist(XMLDEV, "/xmldata/mode"));
		if(in_array($dev, self::$Valsp)) $_SESSION[TMPDATA]["DEV"]["devmode"] = $dev;
		if($_SESSION[TMPDATA]["DEV"]["devmode"] == "on") {
			error_reporting(E_ALL ^ E_NOTICE);
		} else error_reporting(0);
		$_SESSION[TMPDATA]["DEV"]["Count"] = 0;
	}
	
	### Affiche un commentaire (mais ne le stocke pas dans la pile)
	public static function notice($comment, $ajoutPile = true) {
		if($_SESSION[TMPDATA]["DEV"]["devmode"] == "on") {
			// echo($comment."<br />");
			if($ajoutPile) self::comment($comment);
		}
	}

	public static function dumpdata($tableau, $titre = null, $haut = 100) {
		if($_SESSION[TMPDATA]["DEV"]["devmode"] == "on") {
			if($haut != "auto") $haut = $haut."px";
			echo("<pre style='width:90%;height:".$haut.";overflow:auto;border:1px solid #999;border-left:8px solid #999;font-size:10px;'>");
			if($titre) echo("<div style='color:#999;font-weight:bolder;border-bottom:1px dotted #999;background-color:#DDD;'>".$titre." (dump)</div>");
			var_dump($tableau);
			echo("</pre>");
		}
	}

	public static function printdata($tableau, $titre = null, $haut = 100) {
		if($_SESSION[TMPDATA]["DEV"]["devmode"] == "on") {
			if($haut != "auto") $haut = $haut."px";
			echo("<pre style='width:90%;height:".$haut.";overflow:auto;border:1px solid #999;border-left:8px solid #999;font-size:10px;'>");
			if($titre) echo("<div style='color:#999;font-weight:bolder;border-bottom:1px dotted #999;background-color:#DDD;'>".$titre." (print)</div>");
			print_r($tableau);
			echo("</pre>");
		}
	}



	##########################
	### GESTION DE LA PILE ###
	##########################

	### ajoute un commentaire dans la pile
	public static function comment($comment, $mode = "normal") {
		if($_SESSION[TMPDATA]["DEV"]["devmode"] == "on") {
			$modes = array("normal","important","error");
			if(!in_array($mode, $modes)) $mode=$modes[0];
			$time = microtime(true) - $_SESSION[TMPDATA]["DEV"]["initTime"];
			$t = $_SESSION[TMPDATA]["DEV"]["Count"];
			$_SESSION[TMPDATA]["DEV"]["comment"][$t]["time"]		= $time;
			$_SESSION[TMPDATA]["DEV"]["comment"][$t]["comment"]	= $comment;
			$_SESSION[TMPDATA]["DEV"]["comment"][$t]["mode"]		= $mode;
			$_SESSION[TMPDATA]["DEV"]["Count"]++;
		}
	}
}
?>
