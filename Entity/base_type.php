<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;
// Base
use labo\Bundle\TestmanuBundle\Entity\base_entity;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity(fields={"nomcourt"}, message="Ce nom abrégé existe déjà.")
 */
abstract class base_type extends base_entity {


	public function __construct() {
		parent::__construct();
	}

	public function __call($name, $arguments = null) {
		switch ($name) {
			case 'is'.ucfirst($this->getName()):
				$reponse = true;
				break;
			default:
				$reponse = false;
				break;
		}
		return $reponse;
	}

	public function getParentName() {
		return parent::getName();
	}

	public function getName() {
		return 'base_type';
	}

	/**
	 * @ORM/PreUpdate
	 * @ORM/PrePersist
	 */
	public function verifBase_type() {
		$verifMethod = 'verif'.ucfirst($this->getParentName());
		if(method_exists($this, $verifMethod)) {
			$this->$verifMethod();
		}
		$this->defineNomCourt();
	}

	/**
	 * @Assert/True(message = "Cette entité n'est pas valide.")
	 */
	public function isBase_typeValid() {
		$valid = true;
		$validMethod = 'is'.ucfirst($this->getParentName()).'Valid';
		if(method_exists($this, $validMethod)) {
			$valid = $this->$validMethod();
		}
		// autres vérifications, si le parent est valide…
		if($valid === true) {
			//
		}

		return $valid;
	}



}
