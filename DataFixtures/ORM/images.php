<?php

namespace labo\Bundle\TestmanuBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
// container
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
// Entité
use labo\Bundle\TestmanuBundle\Entity\image;

class images extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {
	private $ord			= 121;			// Ordre de chargement fixtures
	private $entity			= "labo\\Bundle\\TestmanuBundle\\Entity\\image";		// nom de l'entité
	private $container;
	private $manager;

	private $aetools;
	private $imagetools;

	public function getOrder() { return $this->ord; } // l'ordre dans lequel les fichiers sont chargés

	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
		// services dossiers/fichiers
		$this->aetools = $this->container->get('acmeGroup.aetools');
		// service images
		$this->imagetools = $this->container->get('acmeGroup.imagetools');

		//efface toutes les anciennes images
		foreach ($this->imagetools->getAllDossiers() as $key => $value) {
			$path = $this->aetools->setWebPath("images/".$value["nom"]."/");
			echo("test dossier ".getcwd()."/"."web/images/".$value["nom"]);
			if($path !== false) {
				echo("\n  - ".$this->aetools->getCurrentPath()." = effacement des fichiers\n");
				// $listFiles = $this->readAll(".+");
				$this->aetools->findAndDeleteFiles(FORMATS_IMAGES);
				echo("  - ".$this->aetools->getCurrentPath()." = effacement du dossier\n");
				$this->aetools->deleteDir($this->aetools->getCurrentPath());
			} else echo(" : non existant\n");
		}
		$this->aetools->setWebPath();
	}

	public function load(ObjectManager $manager) {
		// Remise à zéro de l'auto-incrément
		$this->manager = $manager;
		$connection = $this->manager->getConnection();

		// récupération du service entitiesGeneric
		$this->EntityService = $this->container->get('acmeGroup.entities')->defineEntity($this->entity);

		$connection->exec("ALTER TABLE ".$this->EntityService->getEntiteName()." AUTO_INCREMENT = 1;");

		$entityL = $this->container->get('acmeGroup.fixturesLoader')->loadEntity($this->EntityService, $this->manager);

		if($entityL !== false) {
			echo("Lignes de l'entité enregistrées : ".$this->entity."\n");

			// echo("********************************\n");
			// echo("** ENREGISTREMENT DES IMAGES. **\n");
			// echo("********************************\n");
			// $images = $manager->getRepository("labo\\Bundle\\TestmanuBundle\\Entity\\image")->findAll();
			// foreach($images as $ent) {
			// 	$this->imagetools->loadImageFile($ent);
			// 	$this->imagetools->deleteCurtImage();
			// }
		}
	}


}