<?php
// src/AcmeGroup/services/entitiesServices/collection.php

namespace AcmeGroup\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AcmeGroup\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use AcmeGroup\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class collection extends entitiesGeneric {
	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->defineEntity("collection");
	}

	/**
	 * getDiaporama
	 * Renvoie la collection selon le slug
	 * @param string $collSlug
	 * @return AcmeGroup\services\entitiesServices\collection
	 */
	public function getDiaporama($collSlug = null) {
		if($collSlug === null) $collSlug = "intro";
		$r = $this->getRepo()->findDiapoBySlug($collSlug);
		if(count($r) < 1) $r = null;
			else $r = $r[0];
		return $r;
	}



}

?>
