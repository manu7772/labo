<?php
// src/AcmeGroup/services/entitiesServices/presse.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class presse extends entitiesGeneric {
	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->defineEntity("presse");
	}

	/**
	 * getpresseById
	 * @param string $id
	 * @return presse
	 */
	public function getpresseById($id) {
		$presse = $this->getRepo()->find($id);
		if(is_object($presse)) return $presse;
			else return false;
	}

	/**
	 * getpressesByTags
	 * récupère les articles de presse selon les tags passés en paramètre
	 * @param string/array $tagSlug
	 * @return array $data["presses"][nomTag][nompresse] = objet presse
	 */
	public function getpressesByTags($tagSlug) {
		if(is_string($tagSlug)) $tagSlug = array($tagSlug);
		$data["tagSlug"] = $tagSlug;
		$data["presses"] = $this->getRepo()->findArtByTag($tagSlug);
		return $data;
	}


	/**
	 * check
	 * @return aeReponse
	 */
	// public function check() {
	// 	$r = array();
	// 	$presses = $this->getRepo()->findAll();
	// 	foreach($presses as $presse) {
	// 		// calcul des prix (TTC/HT/etc.)
	// 		$presse->setPrix($presse->getPrix()); // ---> recalcule prix HT automatiquement
	// 		// ventes
	// 		$this->defineEntity("facture");
	// 		$nb = $this->getRepo()->getNbVentespresse($presse);
	// 		$before = $presse->getVentes();
	// 		if($before != $nb) {
	// 			$r[$presse->getId()]["ventes"]["format"] = "integer";
	// 			$r[$presse->getId()]["ventes"]["before"] = $before;
	// 			$r[$presse->getId()]["ventes"]["after"] = $nb;
	// 			$presse->setVentes($nb);
	// 		}
	// 		$this->restitutePreviousEntity();
	// 		// notation
	// 		$this->defineEntity("votepresse");
	// 		$nb = $this->getRepo()->getCalcNotepresse($presse);
	// 		$before = $presse->getNotation();
	// 		if($before != $nb) {
	// 			$r[$presse->getId()]["notation"]["format"] = "integer";
	// 			$r[$presse->getId()]["notation"]["before"] = $before;
	// 			$r[$presse->getId()]["notation"]["after"] = $nb;
	// 			$presse->setNotation($nb);
	// 		}
	// 		$this->restitutePreviousEntity();
	// 	}
	// 	// enregistrement
	// 	// $this->defineEntity("presse");
	// 	$this->getEm()->flush();
	// 	return new aeReponse(1, $r, "Check presses terminé");
	// }

}

?>
