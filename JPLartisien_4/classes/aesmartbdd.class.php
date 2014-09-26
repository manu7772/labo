<?php
###########################################
### VERSION	1.20						###
### NOM		AeSmartBDD	    			###
### DATE	07/04/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* 
Utilisation de la base de données
*********************************
Nom de la table : $nom
Nom des champs : $nom + "_" + .....

Utilisation des commentaires :
Type de controle :
	- voir $formatsCTRL
	- IX = index (ne pas utiliser)
	- listeBDD|table|col(!)	--> pour prendre une liste (type ENUM) dans une autre partie de la table (!) ATTENTION : pour "col", on supprime les "_" (!)
	- lognu / logmp			--> login, nom d'utilisateur (nu) et mot de passe (mp)
	- acces					--> type d'accès pour l'utilisateur
	
Droit de lecture : 8 bits
	- 11111111 : accès à tous, etc.
Droit de d'écriture : 8 bits
	- 11111111 : accès à tous, etc.
Libellé du formulaire
	- phrase affichée dans le formulaire
Title du formulaire (non obligatoire)
	- phrase affichée au passage du pointeur
*/

/* ************************************* */
/* AeSmartBDD   				         */
/* ************************************* */
CLASS AeSmartBDD {
	private $_BDDx;					//objet BDD
	private $_BDD;					//objet de données sur la BDD
	private $_SQLcmdSend = true;	//concerne la méthode SQLcmd : si true, renvoie le résultat de la requête, si false, renvoie la chaîne de requête (en SQL)

	public $data;					//données accessibles sur la BDD

	public $formatsCTRL = array( // Chaînes PregMatch pour le contrôle des éléments de formulaire
		'IX'		=> "",											// Index de la table
		'lognu'		=> "#^[[:alnum:]]{6,}$#",						// login : nom d'utilisateur
		'logmp'		=> "#^[[:alnum:]]{6,}$#",						// login : mot de passe
		'tel' 		=> "#^[[:digit:]]{10}$#",						// format téléphone (10 chiffres)
		'mail'		=> "#^[[:alnum:]]+([\.-_]?[[:alnum:]]+)*@[[:alnum:]]+([\.-_]?[[:alnum:]]+)*\.([[:alpha:]]{2,4})$#",		// format d'adresse email
		'url'		=> "#(((https?|ftp)://(w{3}\.)?)(?<!www)(\w+-?)*\.([a-z]{2,4}))#",	// format d'url internet
		'text'		=> "#[a-zA-Z0-9]#",								// format texte standard (tous caractères)
		'textnom'	=> "#[-'a-zA-Z]#",								// format texte de type nom, prénom, etc.
		'textarea'	=> "#[[:alnum:]]#",								// format texte de type nom, prénom, etc.
		'chiffre'	=> "#^[[:digit:]]$#",							// format chiffre (n'importe quel chiffre)
		'date1'		=> "#^[0-9]{2}/[0-9]{2}/[0-9]{2,4}$#",			// format date : 00/00/00 ou 00/00/0000
		'date2'		=> "#^[0-9]{1,2} [a-zA-Z] [0-9]{4}$#",			// format date : 00 mois 0000
		'heure'		=> "#^[0-9]{2}:[0-9]{2}:[0-9]{2}$#",			// format heure : 00:00:00
		'timestamp'	=> "#^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$#",		// format temps : 2012-06-16 03:02:11
		'liste'		=> "",											// type ENUM
		'listeBDD'	=> "",											// type ENUM d'après autre entrée BDD
		'acces'		=> "#[[:alnum:]]#",								// 1 ou O sur 8 bits -> droits d'accès
		'bool'		=> "",											// 1 ou O
		'IP'		=> "#^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#",	// format adresse IP
		'nc'		=> "");											// ne nécessite pas de contrôle

	### Construction de classe
	### $fichier = nom du fichier XML de données sur la base
	function __construct($fichier=null) {
		// Si non renseigné, utilisation du fichier par défaut
		if(!file_exists($fichier)) $fichier = XMLBDD;
		$data = AeQuickXML::LoadSXD($fichier, '/BDD/serveur[@nom="'.$_SERVER["SERVER_NAME"].'"]');
		if(is_array($data)) $data = $data[0];
		if(is_object($data)) {
			$this->_BDD = $data;
			$this->BDDopen();
		}
	}
	
	function __destruct() {
		$this->_BDDx->close();
	}

	### Ouvre la BDD
	private function BDDopen() {
		$this->_BDDx = new mysqli($this->_BDD->client, $this->_BDD->user, $this->_BDD->passe, $this->_BDD->name);
		if($this->_BDDx->connect_error) die("Erreur de connexion à la BDD : (".$this->_BDDx->connect_errno.") ".$this->_BDDx->connect_error);
		$this->_Loaddata();
	}

	### Charge les données d'accès serveur dans les données publiques
	private function _Loaddata() {
		foreach($this->_BDD as $nom => $val) {
			$this->data[$nom] = AeTools::xmlstr($val);
		}
	}

	### Envoie le résultat d'une requête SQL dans un tableau
	### $sql = requête en SQL
	public function request($sql) {
		$r=array();
		$result = $this->_BDDx->query($sql);
		// echo('Retour SQL : '.$this->_BDDx->errno." / ".$this->_BDDx->error."<br />");
		if($result->num_rows > 0) {
			while($line = $result->fetch_assoc()) $r[] = $line;
			$result->close();
		} else $r = $this->_BDDx->errno;
		return $r;
	}

	### Crée et envoie une requête SQL d'enregistrement d'après $data = tableau associatif
	### $mode = INSERT ou UPDATE
	### $table = table appelée
	### $SQLcmdSend = true pour lancer requête et renvoyer le résultat de requête / false pour renvoyer le texte SQL de requête uniquement
	public function SQLcmd($mode, $table, $data, $SQLcmdSend=true) {
		$r=false;
		//Vérification existance des champs :
		switch($mode) {
			case('INSERT') :
				$sql = "INSERT INTO `".$this->_BDD->name."`.`".$table."` (`";
				$IN=$OUT=$it_IN=$it_OUT="";
				foreach($data as $nomch => $val) {
					$IN.=$it_IN.$nomch;
					$it_IN="`, `";
					if($val != "") $v='"'.utf8_decode($val).'"'; else $v='NULL';
					$OUT.=$it_OUT.$v;
					$it_OUT=", ";
				}
				$sql.=$IN."`) VALUES (".$OUT.");";
				break;
			case('UPDATE') :
				$excl=array("membre_ID");
				$sql = "UPDATE `".$this->_BDD->name."`.`".$data."` SET";
				$vir="";
				foreach($data as $nomch => $val) {
					if(!in_array($nomch, $excl)) $sql.=$vir." `".$nomch."` = '".utf8_decode($val)."' ";
					$vir=",";
				}
				$sql.="WHERE `".$table."`.`".$this->_FindColID($table)."` = ".$data['ID'].";";
				break;
			default :
				$sql = false;
				break;
		}
		if($SQLcmdSend==true) return $this->request($sql);
		else return $sql;
	}

	### Récupère les données d'une ligne de $table selon l'ID
	private function _ReadBDD($table, $ID) {
		$cha=$this->_FindColID($table);
		$sql = 'SELECT * FROM `'.$table.'` WHERE `'.$cha.'` = $ID LIMIT 1;';
		$result = $this->_BDDx->query($sql);
		if ($result->num_rows > 0) {
			$r=array();
			$row = $result->fetch_assoc();
			$result->close();
			foreach($row as $nom => $val) $r[$nom]=utf8_encode($val);
		} else $r=false;
		return $r;
	}

	### Renvoie le nom du champs contenant les ID de la $table
	private function _FindColID($table) {
		$sql = 'SHOW FULL COLUMNS FROM '.$table.' WHERE comment = "IX";';
		$result = $this->_BDDx->query($sql);
		if ($result->num_rows > 0) {
			$s = $result->fetch_assoc();
			$result->close();
			$r = $s['Field'];
		} else $r=false;
		return $r;
	}

	/* ************************************* */
	/* Fonctions de LOGIN                    */
	/* ************************************* */
	
	public function login($nu,$mp,$table) {
		$r=false;
		$result = $this->BDDx->query("SELECT * FROM `$table` WHERE `membre_login` = '$nu' AND `membre_passe` = '$mp' LIMIT 1");
		if ($result->num_rows > 0) $r = $result->fetch_assoc();
		return $r;
	}
	
	/* ************************************* */
	/* Fonctions de structure                */
	/* ************************************* */
	
	### Renvoie la liste des tables de la base
	public function list_tables() {
		$r=false;
		$result = $this->_BDDx->query("SHOW TABLES");
		if ($result->num_rows > 0) {
			$r=array();
			while ($row = $result->fetch_assoc()) $r[]=utf8_encode(strval($row['Tables_in_'.$this->_BDD->name]));
		}
		return $r;
	}
	### Renvoie la liste des champs de la $table
	public function list_champs($table, $complete=null) {		
		$r=false;
		$result = $this->_BDDx->query("SHOW FULL COLUMNS FROM ".$table);
		if ($result->num_rows > 0) {
			$r=array();
			while ($row = $result->fetch_assoc()) {
				if($complete) $r[]=$row;
				else $r[$row['Field']]=utf8_encode($row['Comment']);
			}
		}
		return $r;
	}
	# Renvoie le nom du champ contenant l'ID, dans la table $table
	public function FindChampID($table) {
		$sql = 'SHOW FULL COLUMNS FROM '.$table.' WHERE comment = "IX"';
		$result = $this->_BDDx->query($sql);
		if ($result->num_rows > 0) {
			$a = $result->fetch_assoc();
			$r=$a['Field'];
		} else $r=null;		$result = $this->BDDx->query("SHOW FULL COLUMNS FROM ".$table);
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				if($row['Field'] == $champ) {
					$val=utf8_encode($row['Type']);
				}
			}
		}
		if(is_string($val)) {
			$r=array();
			$liste=explode("'",$val);
			foreach($liste as $num => $tx) if($num%2==1) $r[]=$tx;
		} else $r=false;
	
		return $r;
	}
	# Renvoie la liste des commentaires de la table
	// $table=nom de la table
	// (si $complete <> null = renvoie les détails dans le tableau complet)
	private function commentable($table, $complete=null) {
		$r=false;
		$result = $this->_BDDx->query("SHOW FULL COLUMNS FROM ".$table);
		if ($result->num_rows > 0) {
			$r=array();
			while ($row = $result->fetch_assoc()) {
				if($complete) $r[]=$row;
				else $r[$row['Field']]=utf8_encode($row['Comment']);
			}
		}
		return $r;
	}


	/* ************************************* */
	/* Préférences utilisateur               */
	/* ************************************* */

	// Récupère les préférences de l'utilisateur d'ID = $ID
	// Renvoie un tableau
	public function getUSERprefs($ID) {
		$sql = "SELECT `membre_prefs` FROM `aequa_membre` WHERE `membre_ID` = ".$ID." ";
		$seri = $this->request($sql);
		echo("Résultat recherche (id = ".$ID.") : <br />");
		var_dump($seri);
		echo("<br /><br />");
		return unserialize(urldecode($seri[0]["membre_prefs"]));
	}

	// Enregistre les préférences utilisateur depuis un tableau
	// --> Les noms des données doivent commencer par "Prefs_" pour être pris en compte
	// $data["USER"] = ID de l'utilisateur
	public function saveUSERprefs($data) {
		$sdat = $this->getUSERprefs($data["USER"]);
		foreach($data as $nom => $val) {
			if(substr($nom, 0, 6) == "Prefs_") $sdat[str_replace("Prefs_", "", $nom)] = $val;
		}
		$seri = urlencode(serialize($sdat));
		$sql = "UPDATE `".$this->_BDD->name."`.`aequa_membre` SET `membre_prefs` = '".$seri."' WHERE `aequa_membre`.`membre_ID` = ".$data["USER"].";";
		$this->_BDDx->query($sql);
	}

	// Enregistre en BDD les données statistiques
	public function AEtracker($ID, $IP, $code, $origin = null) {
		if($origin == null) $origin = "NULL";
			else $origin = "'".$origin."'";
		$sql = "INSERT INTO `".$this->_BDD->name."`.`aestat` (`aestat_ID`, `aestat_membreID`, `aestat_IP`, `aestat_operation`, `aestat_time`, `aestat_provenance`) VALUES (NULL, '".$ID."', '".$IP."', '".$code."', CURRENT_TIMESTAMP, ".$origin.");";
		$this->_BDDx->query($sql);
	}

}

?>