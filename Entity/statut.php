<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;
use labo\Bundle\TestmanuBundle\Entity\base_type;
// aeReponse
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;

/**
 * statut
 *
 * @ORM\Entity
 * @ORM\Table(name="statut")
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\statutRepository")
 * @UniqueEntity(fields={"nom"}, message="Ce statut existe déjà.")
 */
class statut extends base_type {

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
		return 'statut';
	}

	/**
	 * @ORM/PreUpdate
	 * @ORM/PrePersist
	 */
	public function verifStatut() {
		$verifMethod = 'verif'.ucfirst($this->getParentName());
		if(method_exists($this, $verifMethod)) {
			$this->$verifMethod();
		}
		$this->defineNomCourt();
	}

	/**
	 * @Assert/True(message = "Ce statut n'est pas valide.")
	 */
	public function isStatutValid() {
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

