<?php
###########################################
### VERSION	1.00						###
### NOM		FPtrisbdd 					###
### DATE	19/06/2013					###
### AUTEUR	Emmanuel Dujardin			###
###########################################

/* ************************************* */
/* FPtrisbdd					         */
/* ************************************* */
CLASS FPtrisbdd {

	private $BDD;

	function __construct() {
		$this->BDD = new AeSmartBDD();
	}

	public function tridomaines($doms) {
		$i = 0;
		$cpt = array();
		foreach($doms as $nom => $val) if(substr($nom, 0, 4) == "dom_") {
			$ex = explode("_", $nom);
			if(in_array($ex[2], $cpt)) $idx = $ex[2];
				else {
					$cpt[$i] = $ex[2];
					$i++;
				}
			$tb[$i]["dom_".$ex[1]] = $val;
			// SQLcmd
		}
		// echo("<br />".sizeof($tb)." résultats trouvés<br />");
		// foreach($tb as $ord => $v) {
		// 	$v['dom_ord'] = $ord;
		// 	foreach($v as $nom => $val) echo($ord." => ".$nom." : ".$val."<br />");
		// }

		$this->saveTable("topo_domaines");
		$sql = "TRUNCATE TABLE `topo_domaines`";
		$this->BDD->request($sql);

		foreach($tb as $ord => $v) {
			$v['dom_ord'] = $ord;
			$sql = $this->BDD->SQLcmd('INSERT', 'topo_domaines', $v, false);
			// echo("<br />".$sql."<br /><br />");
			$a = $this->BDD->request($sql);
			echo($a." / ");
			if($a != 0) {
				die($a);
				loadTable("topo_domaines");
			}
		}
	}

	private function saveTable($nom) {
		$sql = "";
	}

	private function loadTable($nom) {
		$sql = "";
	}
}