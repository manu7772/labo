<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\MappedSuperclass
 */
abstract class fournisseur {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=100)
	 */
	protected $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="string", length=255)
	 */
	protected $descriptif;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="adresse", type="string", length=255)
	 */
	protected $adresse;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="tel", type="string", length=20)
	 */
	protected $tel;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cp", type="string", length=5)
	 */
	protected $cp;



	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set nom
	 *
	 * @param string $nom
	 * @return fournisseur
	 */
	public function setNom($nom) {
		$this->nom = $nom;
	
		return $this;
	}

	/**
	 * Get nom
	 *
	 * @return string 
	 */
	public function getNom() {
		return $this->nom;
	}

	/**
	 * Set descriptif
	 *
	 * @param string $descriptif
	 * @return fournisseur
	 */
	public function setDescriptif($descriptif) {
		$this->descriptif = $descriptif;
	
		return $this;
	}

	/**
	 * Get descriptif
	 *
	 * @return string 
	 */
	public function getDescriptif() {
		return $this->descriptif;
	}

	/**
	 * Set adresse
	 *
	 * @param string $adresse
	 * @return fournisseur
	 */
	public function setAdresse($adresse) {
		$this->adresse = $adresse;
	
		return $this;
	}

	/**
	 * Get adresse
	 *
	 * @return string 
	 */
	public function getAdresse() {
		return $this->adresse;
	}

	/**
	 * Set tel
	 *
	 * @param string $tel
	 * @return fournisseur
	 */
	public function setTel($tel) {
		$this->tel = $tel;
	
		return $this;
	}

	/**
	 * Get tel
	 *
	 * @return string 
	 */
	public function getTel() {
		return $this->tel;
	}

	/**
	 * Set cp
	 *
	 * @param string $cp
	 * @return fournisseur
	 */
	public function setCp($cp) {
		$this->cp = $cp;
	
		return $this;
	}

	/**
	 * Get cp
	 *
	 * @return string 
	 */
	public function getCp() {
		return $this->cp;
	}
}
