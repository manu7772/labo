<?php
// src/AcmeGroup/services/aetools/html2pdf.php

namespace labo\Bundle\TestmanuBundle\services\aetools;

use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;

use app\Resources\classes\html2pdf\HTML2PDF;

class html2pdf {

	protected $container;
	protected $locale;
	protected $twig;

	public function __construct($container, $locale, $templating) {
		$this->container = $container;
		$this->locale = $locale;
		$this->twig = $templating;
	}

	public function newPDF() {
		$this->PDF = new HTML2PDF();
	}

}
?>
