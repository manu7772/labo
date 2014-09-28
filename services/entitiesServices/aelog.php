<?php
// src/AcmeGroup/services/entitiesServices/aelog.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class aelog extends entitiesGeneric {

	protected $service = array();
	protected $container;
	protected $resultCourant;

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->loadCurrentUser(); // Chargement de l'utilisateur courant !!! IMPORTANT !!!
		$this->resultCourant = array();
		// $this->setCurrentEntity("aelog");
		$this->defineEntity("aelog");
	}

	public function createNewLog(
		$commentaire = null,
		$nom = null,
		$url = null,
		$parameter = null,
		$controller = null,
		$code = null,
		$ip = null,
		$user = null,
		$dateCreation = null,
		$versionSlug = null
		) {
		if(($this->modeFixtures === false) && ($this->aetools->getEnv() === "prod")) {
			if($nom === null) $nom = $this->aetools->getRoute();
			if($ip === null) $ip = $this->aetools->getIP();
			if($url === null) $url = $this->aetools->getURL();
			if($parameter === null) $parameter = $this->aetools->getParameters();
			if($controller === null) $controller = $this->aetools->getController();
			$ver = $this->container->get("session")->get('version');
			if($versionSlug === null) $versionSlug = $ver["slug"];
			if($user === null) {
				if(($this->user !== false) && ($this->user !== null)) {
					$user = $this->user;
				}
			}
			if(is_string($dateCreation)) {
				$dateCreation = new \DateTime($dateCreation);
			}
			// Création de l'entité
			$aelog = $this->newObject(true);
			// remplissage…
			$aelog->setNom($nom);
			$aelog->setUrl($url);
			$aelog->setParameter($parameter);
			$aelog->setController($controller);
			$aelog->setCode($code);
			$aelog->setIp($ip);
			$aelog->setUser($user);
			if($dateCreation !== null) $aelog->setDateCreation($dateCreation);
			$aelog->setCommentaire($commentaire);
			$aelog->setVersionSlug($versionSlug);
			// enregistrement
			$this->getEm()->persist($aelog);
			$this->getEm()->flush();
		}
	}

	public function createNewLogAuto() {
		$this->createNewLog();
		return $this;
	}

	// public function createNewLoginUser($user) {
	// 	if($this->modeFixtures === false) {
	// 		$route 	= $this->aetools->getRoute();
	// 		$this->createNewLog($route, null, null, $user);
	// 	}
	// 	return $this;
	// }

	public function findByType($type) {
		switch($type) {
			case 'general':
				$r = $this->getRepo()->findByRoute();
				break;
			default:
				$r = $this->getRepo()->findByRoute();
				break;
		}
		return $r;
	}

	/**
	 * findByIp
	 * Renvoie les statistiques sur un ou plusieurs articles
	 * @param string $ip - ip à traiter
	 * @param dateDebut - date de début de recherche
	 * @param dateDebut - date de fin de recherche (aujourd'hui par défaut)
	 * @return array
	 */
	public function findByIp($ip, $dateDebut = null, $dateFin = null) {
		return $this->getRepo()->findByIp($ip, $dateDebut, $dateFin);
	}

	/**
	 * findArticle
	 * Renvoie les statistiques sur un ou plusieurs articles
	 * @param string $articleSlug - slug de l'article
	 * @param dateDebut - date de début de recherche
	 * @param dateDebut - date de fin de recherche (aujourd'hui par défaut)
	 * @return array
	 */
	public function findArticle($articleSlug, $dateDebut = null, $dateFin = null) {
		return $this->getRepo()->findArticle($articleSlug, $dateDebut, $dateFin);
	}

	/**
	 * trieByDate
	 * Trie un array de résultats Aelogs par périodes : années / mois / semaines / jours / heure…
	 * @param array $data - array of \labo\Bundle\TestmanuBundle\Entity\aelog
	 * @param array $tempo - affinage de tri
	 * @return array
	 */
	public function trieByDate($data, $tempo = "jour", $methode = "getDateCreation") {
		if(is_object($data)) $data = array($data);
		// vérifie que la méthode existe
		if(is_array($data)) {
			reset($data);
			$objet = current($data);
			if(method_exists($objet, $methode)) {
				$r = array();
				// création du schéma de période
				switch($tempo) {
					case "an": 		$r["schema"] = "Y"; break;
					case "mois": 	$r["schema"] = "Y-m"; break;
					case "semaine": $r["schema"] = "Y-W"; break;
					case "jour": 	$r["schema"] = "Y-m-d"; break;
					case "heure": 	$r["schema"] = "Y-m-d-H"; break;
					case "minute": 	$r["schema"] = "Y-m-d-H-i"; break;
					case "seconde": $r["schema"] = "Y-m-d-H-i-s"; break;
					default: 		$r["schema"] = "Y-m-d"; break;
				}
				foreach($data as $num => $item) {
					$tri = explode("-", $item->$methode()->format($r["schema"]));
					switch(count($tri)) {
						case 1: $r["data"][$tri[0]][$num] = $item; break;
						case 2: $r["data"][$tri[0]][$tri[1]][$num] = $item; break;
						case 3: $r["data"][$tri[0]][$tri[1]][$tri[2]][$num] = $item; break;
						case 4: $r["data"][$tri[0]][$tri[1]][$tri[2]][$tri[3]][$num] = $item; break;
						case 5: $r["data"][$tri[0]][$tri[1]][$tri[2]][$tri[3]][$tri[4]][$num] = $item; break;
						case 6: $r["data"][$tri[0]][$tri[1]][$tri[2]][$tri[3]][$tri[4]][$tri[5]][$num] = $item; break;
						case 7: $r["data"][$tri[0]][$tri[1]][$tri[2]][$tri[3]][$tri[4]][$tri[5]][$tri[6]][$num] = $item; break;
					}
				}
			} else $r = null;
		} else $r = null;
		return $r;
	}

}








?>
