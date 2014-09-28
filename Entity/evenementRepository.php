<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\baseRepository;

/**
 * evenementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class evenementRepository extends baseRepository {

	private $typesEvent = null;
	private $typesEventMODELE = null;
	
	/**
	 * findEventsByType
	 * Liste des évènement du type $type
	 *
	 * @param string/array $type
	 * @return array
	 */
	public function findEventsByType($type) {
		$qb = $this->createQueryBuilder('element');
		$qb->join('element.typeEvenement', 'te')
			->where($qb->expr()->in('te.slug', $this->defineTypeEvents($type)));
			// ->setParameter('slug', $type);
		// $qb = $this->excludeExpired($qb);  ///// ---->>> à voir problème sur serveur mais pas en local !!!
		// $qb = $this->withVersion($qb);
		$qb = $this->defaultStatut($qb);
		$qb->orderBy('element.datedebut', 'DESC');
		return $qb->getQuery()->getResult();
	}

	/**
	 * getEventsByDate
	 * récupère les évènements prochains
	 * @param integer $joursAvant (annonces les évènements)
	 * @return array
	 */
	public function getLgoc1Events($limiteMois = 6) {
		$this->initTypeEvents();
		$date = new \Datetime();
		$qb = $this->createQueryBuilder('element');
		$qb->join('element.typeEvenement', 'te')
			->where($qb->expr()->in('te.slug', $this->getTypeEvents()))
			// ->andWhere('element.datedebut >= :date')
			// ->setParameter("date", $date)
			;
		// statuts / versions / ordre
		$qb = $this->withVersion($qb);
		$qb = $this->defaultStatut($qb);
		$qb->orderBy('element.datedebut', 'DESC');
		return $qb->getQuery()->getResult();
	}

	/**
	 * getPastEvents
	 * récupère les évènements prochains
	 * @param integer $joursAvant (annonces les évènements)
	 * @return array
	 */
	public function getPastEvents() {
		$this->initTypeEvents();
		$date = new \Datetime();
		$qb = $this->createQueryBuilder('element')
			->join('element.typeEvenement', 'te')
			->orderBy('element.datedebut', 'DESC');
		return $qb->getQuery()->getResult();
	}


	/**
	 * getBestEvent
	 * récupère l'évènement le plus approprié selon la date fournie (par défaut, date actuelle)
	 * @param integer $joursAvant (nombre de jours avant la date de l'évènement)
	 * @param datetime $date
	 * @return evenement
	 */
	public function getBestEvent($joursAvant = 15, $date = null) {
		$this->initTypeEvents();
		$qb = $this->createQueryBuilder('element');
		$qb = $this->withVersion($qb);
		$qb = $this->defaultStatut($qb);
		$r = $qb->getQuery()->getResult();
		return $r[0];
	}




	/**
	 * initTypeEvents
	 * 
	 */
	public function initTypeEvents($force = false) {
		if(($this->typesEventMODELE === null) || ($this->typesEvent === null) || ($force === true)) {
			$this->typesEventMODELE = array();
			$this->typesEvent = array();
			$listTE = $this->_em->getRepository("AcmeGroupLaboBundle:typeEvenement")->findAll();
			// $TER = new typeEvenementRepository($this->em, $this->cmdata);
			// $listTE = $TER->findAll();
			// $listTE = array("expositions", "salons-foires", "manifestations-culturelles", "pub");
			foreach($listTE as $elem) {
				$this->typesEventMODELE[] = $elem->getSlug();
			}
			$this->typesEvent = $this->typesEventMODELE;
			// var_dump($this->typesEvent);
		}
		return $this->typesEvent;
	}

	/**
	 * getTypeEvents
	 * @return array
	 */
	public function getTypeEvents() {
		$this->initTypeEvents();
		return $this->typesEvent;
	}

	/**
	 * defineTypeEvents
	 * @param array/string $types
	 * @return array
	 */
	public function defineTypeEvents($types) {
		if($types == "all") {
			$this->initTypeEvents(true);
		} else {
			$this->initTypeEvents();
			$typ = array();
			$this->typesEvent = array();
			if(is_string($types)) $typ[0] = $types; else if(is_array($types)) $typ = $types;
			foreach($typ as $nomtype) {
				if(in_array($nomtype, $this->typesEventMODELE)) {
					$this->typesEvent[] = $nomtype;
				}
			}
		}
		return $this->typesEvent;
	}

	/**
	 * addTypeEvents
	 * @param array/string $types
	 */
	public function addTypeEvents($types) {
		if($types == "all") {
			$this->initTypeEvents(true);
		} else {
			$this->initTypeEvents();
			$typ = array();
			if(is_string($types)) $typ[] = $types; else if(is_array($types)) $typ = $types;
			foreach($typ as $nomtype) {
				if(in_array($nomtype, $this->typesEventMODELE) && !(in_array($nomtype, $this->typesEvent))) {
					$this->typesEvent[] = $nomtype;
				}
			}
		}
		return $this->typesEvent;
	}

	/**
	 * suppTypeEvents
	 * @param array/string $types
	 */
	public function suppTypeEvents($types) {
		$this->initTypeEvents();
		$typ = array();
		if(is_string($types)) $typ[] = $types; else if(is_array($types)) $typ = $types;
		foreach($typ as $nomtype) {
			if(in_array($nomtype, $this->typesEvent)) {
				$keys = array_keys($this->typesEvent, $nomtype);
				foreach($keys as $key) {
					$this->typesEvent[$key] = null;
					unset($this->typesEvent[$key]);
				}
			}
		}
		return $this->typesEvent;
	}


}
