<?php
// labo/Bundle/TestmanuBundle/services/aetools/aedates.php

namespace labo\Bundle\TestmanuBundle\services\aetools;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;

class aedates {

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
		return $this;
	}


?>
