<?php
// src/AcmeGroup/services/entitiesServices/visite.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

// use Symfony\Component\Templating\EngineInterface;
// use Symfony\Component\DependencyInjection\ContainerInterface;
// use labo\Bundle\TestmanuBundle\services\entitiesServices\entities_generic;
// use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class visite extends Event {
	protected $service = array();
	protected $security_context;
	protected $user;

	public function __construct(UserInterface $user) {
		parent::__construct($container);
		// $this->defineEntity("visite");
		$this->user = $user;
	}

	public function getName() {
		return $this->serviceNom;
	}

	/**
	* serviceEventInit
	* Initialise le service - attention : cette méthode est appelée en requête principale par EventListener !!!
	* 
	* @param FilterControllerEvent $event
	* @param boolean $reLoad
	*/
	public function serviceEventInit(FilterControllerEvent $event, $reLoad = false) {
		$controller = $event->getController();
		echo($this->user->getUsername()."<br />");

		return $this;
	}

	public function getUser() {
		return $this->user;
	}



}

?>
