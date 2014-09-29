<?php
// src/AcmeGroup/services/entitiesServices/atelierCreatif.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class atelierCreatif extends entitiesGeneric {
	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->defineEntity("ficheCreative");
	}

	/**
	 * getThemes
	 * Renvoie la liste des thèmes fiches créatives
	 * @return array
	 */
	public function getThemes() {
		$this->defineEntity("categorie");
		$atelier = $this->getRepo()->findBySlug("atelier-creatif");
		$themes = $this->getRepo()->getChildren($atelier[0]);
		$this->restitutePreviousEntity();
		return $themes;
	}

	/**
	 * getThemesEtFiches
	 * 
	 * @return array
	 */
	public function getThemesEtFiches() {
		$r = array();
		$themes = $this->getThemes();
		foreach($themes as $theme) {
			$r[$theme->getSlug()]["objet"] = $theme;
		}
		// echo("Repo fiches créatives : ".$this->getRepoNameEntite());
		$fiches = $this->getRepo()->findAllFiches();
		foreach($fiches as $fiche) {
			$nomTheme = $fiche->getCategorie()->getSlug();
			if(!isset($r[$nomTheme]["fiches"])) $r[$nomTheme]["fiches"] = array();
			$r[$nomTheme]["fiches"][] = $fiche;
		}
		return $r; 
	}

	/**
	 * getFicheBySlug
	 * @param string $slug
	 * @return AcmeGroup\LaboBundle\Entity\ficheCreative
	 */
	public function getFicheBySlug($slug) {
		$r = $this->getRepo()->getFicheBySlug($slug);
		if(count($r) > 0) $r = $r[0];
			else $r = null;
		return $r;
	}

	/**
	 * check
	 * @return aeReponse
	 */
	public function check() {
		$r = array();
		$ficheCreative = $this->getRepo()->findAll();
		foreach($ficheCreative as $fiche) {
			if($fiche->getDatePublication() < $fiche->getDateCreation()) {
				$r[$fiche->getId()]["datePublication"]["format"] = "Datetime";
				$r[$fiche->getId()]["datePublication"]["before"] = $fiche->getDatePublication();
				$fiche->setDatePublication($fiche->getDateCreation());
				$r[$fiche->getId()]["datePublication"]["after"] = $fiche->getDatePublication();
			}
		}
		$this->getEm()->flush();
		return new aeReponse(1, $r, "Check fiches créatives terminé");
	}

}

?>
