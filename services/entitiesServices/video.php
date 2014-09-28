<?php
// src/AcmeGroup/services/entitiesServices/video.php

namespace AcmeGroup\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AcmeGroup\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use AcmeGroup\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class video extends entitiesGeneric {
	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->defineEntity("video");
	}

	/**
	 * check
	 * @return aeReponse
	 */
	public function check() {
		$r = array();
		$videos = $this->getRepo()->findAll();
		foreach($videos as $video) {
			if($video->getDatePublication() < $video->getDateCreation()) {
				$r[$video->getId()]["datePublication"]["format"] = "Datetime";
				$r[$video->getId()]["datePublication"]["before"] = $video->getDatePublication();
				$video->setDatePublication($video->getDateCreation());
				$r[$video->getId()]["datePublication"]["after"] = $video->getDatePublication();
			}
		}
		$this->getEm()->flush();
		return new aeReponse(1, $r, "Check videos terminé");
	}

}

?>
