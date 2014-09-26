<?php
###########################################
### VERSION	1.00						###
### NOM		AeMessages  				###
### DATE	15/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* AeMessages                            */
/* Gestionnaire de messages et alertes   */
/* ************************************* */
CLASS AeMessages {

	public static function init() {
		$_SESSION[TMPDATA]["messages"]["count"] = 0;
		$_SESSION[TMPDATA]["messages"]["liste"] = array();
		$_SESSION[TMPDATA]["alertes"]["count"] = 0;
		$_SESSION[TMPDATA]["alertes"]["liste"] = array();
	}

	public static function message($mess) {
		$_SESSION[TMPDATA]["messages"]["liste"][$_SESSION[TMPDATA]["messages"]["count"]] = $mess;
		$_SESSION[TMPDATA]["messages"]["count"]++;
	}

	public static function alerte($alrt) {
		$_SESSION[TMPDATA]["alertes"]["liste"][$_SESSION[TMPDATA]["alertes"]["count"]] = $alrt;
		$_SESSION[TMPDATA]["alertes"]["count"]++;
	}

}

?>
