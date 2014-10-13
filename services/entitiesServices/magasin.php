<?php
// src/AcmeGroup/services/entitiesServices/magasin.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class magasin extends entitiesGeneric {
	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->defineEntity("magasin");
	}

	/**
	 * findMagsByCP
	 * Renvoie la liste des magasins selon le code postal $code
	 *
	 * @param string
	 * @return array
	 */
	public function findMagsByCP($code) {
		$liste = $this->getRepo()->findByDept($code);
		return $liste;
	}

	/**
	 * findMagsByVille
	 * Renvoie la liste des magasins selon la ville
	 *
	 * @param string
	 * @return array of magasin
	 */
	public function findMagsByVille($code) {
		$liste = $this->getRepo()->findByVille($code);
		return $liste;
	}

	/**
	 * listeVilleAvecMagasin
	 * Renvoie la liste des villes qui ont au moins un magasin
	 *
	 * @param array --> array des départements
	 * @return array of magasin
	 */
	public function listeVilleAvecMagasin() {
		$liste = $this->getRepo()->findListeVilles();
		return $liste;
	}

	/**
	 * findMag
	 * Renvoie l'objet magasin d'id $id
	 *
	 * @param integer
	 * @return magasin
	 */
	public function findMag($id) {
		$liste = $this->getRepo()->find($id);
		return $liste;
	}

	/**
	 * Liste des magasins sans email
	 *
	 * @return array
	 */
	public function findMagSansMail() {
		$liste = $this->getRepo()->findMagSansMail();
		return $liste;
	}

	/**
	 * check
	 * @return aeReponse
	 */
	public function check() {
		$r = array();
		$magasins = $this->getRepo()->findAll();
		foreach($magasins as $magasin) {
			// Type magasin
			$oldType = $magasin->getTypemagasin();
			$magasin->setTypemagasin($magasin->getTypemagasin());
			$newType = $magasin->getTypemagasin();
			if($oldType !== $newType) {
				$r[$magasin->getId()]["type magasin"]["format"] = "string";
				$r[$magasin->getId()]["type magasin"]["before"] = $oldType;
				$r[$magasin->getId()]["type magasin"]["after"] = $newType;
			}
			// Traitement mails
			$oldEmail = $magasin->getEmail();
			$magasin->setEmail($magasin->getEmail());
			$newEmail = $magasin->getEmail();
			if($oldEmail !== $newEmail) {
				$r[$magasin->getId()]["email"]["format"] = "string";
				$r[$magasin->getId()]["email"]["before"] = $oldEmail;
				$r[$magasin->getId()]["email"]["after"] = $newEmail;
			}
			// traitement initNomformenu
			$oldIMF = $magasin->getNomformenu();
			$magasin->initNomformenu();
			$newIMF = $magasin->getNomformenu();
			if($oldIMF !== $newIMF) {
				$r[$magasin->getId()]["nom for menu"]["format"] = "string";
				$r[$magasin->getId()]["nom for menu"]["before"] = $oldIMF;
				$r[$magasin->getId()]["nom for menu"]["after"] = $newIMF;
			}
			// traitement départements manquants
			$oldDEP = $magasin->getDepartement();
			$newDEP = substr($magasin->getCp()."", 0, 2);
			if($oldDEP !== $newDEP) {
				$r[$magasin->getId()]["departement"]["format"] = "string";
				$r[$magasin->getId()]["departement"]["before"] = $oldDEP;
				$r[$magasin->getId()]["departement"]["after"] = $newDEP;
			}
		}
		$this->getEm()->flush();
		return new aeReponse(1, $r, "Check magasins terminé");
		// return new aeReponse(2, $r, "Check magasin pas encore programmé…");
	}

}

?>
