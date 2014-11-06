<?php
// src/AcmeGroup/services/entitiesServices/evenement.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class evenement extends entitiesGeneric {
	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		// $this->setCurrentEntity("evenement");
		$this->defineEntity("evenement");
	}

	/**
	 * addFindParam
	 * ajoute un paramètre de recherche
	 * @return AcmeGroup\LaboBundle\services\entitiesServices\evenement
	 */
	public function addFindParam($champ, $valeur, $champLié = null) {
		$this->params->addParam($champ, $valeur, $champLié);
		return $this;
	}

	/**
	 * compilePosts
	 * compile les données reçues en post
	 * @return AcmeGroup\LaboBundle\services\entitiesServices\evenement
	 */
	public function compilePosts($post) {
		// $this->params->addParam($champ, $valeur, $champLié)
		return $this;
	}

	/**
	 * findWithParams
	 * lance la recherche en repository avec les paramètres paramForRepo
	 * @return AcmeGroup\LaboBundle\services\entitiesServices\evenement
	 */
	public function findWithParams() {
		// $this->params->addParam($champ, $valeur, $champLié)
		return $this;
	}

	/**
	 * getNEventsByType
	 * récupère $n évènements de typeEvenement (slug) = $type
	 * @param string/array $type
	 * @param integer $n
	 * @return array
	 */
	public function getNEventsByType($type, $n = 1, $rand = false) {
		if($n < 1) $n = 1;
		$ent = $this->getRepo()->findEventsByType($type);
		$data = array();
		if(count($ent) < $n) $n = count($ent);
		if($rand !== false) {
			// mélange aléatoire
			$r = array_rand($ent, $n);
			if(is_array($r)) foreach($r as $newkey => $key) $data[$newkey] = $ent[$key];
				else $data[0] = $ent[$r];
		} else {
			// sans mélange
			$data = array_slice($ent, 0, $n, false);
		}
		return $data;
	}

	/**
	 * getAllEventsByType
	 * récupère tous les évènements de typeEvenement (slug) = $type
	 * @param string/array $type
	 * @param integer $n
	 * @return array
	 */
	public function getAllEventsByType($type, $year) {
		$data = $this->getRepo()->findEventsByType($type, $year);
		return $data;
	}

	/**
	 * getEventsByDate
	 * récupère les entités avec l'objet de paramètres paramForRepo
	 * @return array
	 */
	public function getEventsByDate() {
		$data = array();
		return $data;
	}

	public function classByYear($dates, $debut = true) {
		$r = array();
		foreach($dates as $date) {
			if($debut === true) $d = $date->getDatedebut();
				else $d = $date->getDatefin();
			$r[$d->format("Y")][$date->getId()] = $date;
		}
		return $r;
	}


	/**
	 * classByOneYear
	 * Classe les évènements $date (array) par années
	 * @param array $dates
	 * @param integer $year (ne tient compte que de l'année précisée)
	 * @param boolean $debut (true (défaut) = tient compte de la date de début de l'évènement / false = date de fin)
	 * @return array
	 */
	public function classByOneYear($dates, $year = null, $debut = true) {
		$r = array();
		if($year !== null || !preg_match("#^\d{4}$#", $year)) {
			$year = date("Y");
		}
		foreach($dates as $date) {
			if($debut === true) $d = $date->getDatedebut();
				else $d = $date->getDatefin();
			$r[$d->format("Y")][$date->getId()] = $date;
		}
		return $r;
	}

	/**
	 * getEventsByDate
	 * récupère les évènements prochains
	 * @param integer $limiteMois (ne récupère pas les évènements au-delà de $limiteMois / Si 0 alors pas de limite)
	 * @return array
	 */
	public function getLgoc1Events($limiteMois = 6) {
		$data = $this->getRepo()->getLgoc1Events($limiteMois);
		return $data;
	}

	/**
	 * getBestEvent
	 * récupère l'évènement le plus approprié selon la date fournie (par défaut, date actuelle)
	 * @param integer $joursAvant (nombre de jours avant la date de l'évènement)
	 * @param datetime $date
	 * @return evenement
	 */
	public function getBestEvent($joursAvant = 15, $date = null) {
		$data = $this->getRepo()->getBestEvent($joursAvant, $date);
		return $data;
	}

	/**
	 * check
	 * @return aeReponse
	 */
	public function check() {
		$r = array();
		// var_dump($this->version);
		$evenements = $this->getRepo()->findAll();
		$ver = $this->defineEntity("version")->getRepo()->findBySlug($this->version["slug"]);
		$this->restitutePreviousEntity();
		$versionObj = $ver[0];
		foreach($evenements as $evenement) {
			// Check versions
			$oldVersions = $evenement->getVersions();
			if(count($oldVersions) < 1) {
				// ajoute la version courante, car il n'existe pas de version du tout
				$evenement->addVersion($versionObj);
				$r[$evenement->getId()]["version"]["format"] = "string";
				$r[$evenement->getId()]["version"]["before"] = "(aucune version)";
				$r[$evenement->getId()]["version"]["after"] = $versionObj->getNom();
			}
		}
		$this->getEm()->flush();
		return new aeReponse(1, $r, "Check évènements terminé");
		// return new aeReponse(2, $r, "Check évènements pas encore programmé…");
	}

}

?>
