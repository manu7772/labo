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
abstract class typeEvenement {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=255)
	 */
	protected $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text")
	 */
	protected $descriptif;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="editable", type="boolean")
	 */
	protected $editable;

	/**
	 * @var array
	 *
	 * @ORM\OneToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=true, unique=true)
	 */
	protected $image;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	protected $slug;


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
	 * @return typeEvenement
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
	 * @return typeEvenement
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
	 * Set editable
	 *
	 * @param boolean $editable
	 * @return typeEvenement
	 */
	public function setEditable($editable) {
		$this->editable = $editable;
	
		return $this;
	}

	/**
	 * Get editable
	 *
	 * @return boolean 
	 */
	public function getEditable() {
		return $this->editable;
	}

	/**
	 * Set image
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $image
	 * @return ficheCreative
	 */
	public function setImage(\AcmeGroup\LaboBundle\Entity\image $image = null) {
		$this->image = $image;
	
		return $this;
	}

	/**
	 * Get image
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\image 
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Set slug
	 *
	 * @param integer $slug
	 * @return baseEntity
	 */
	public function setSlug($slug) {
		$this->slug = $slug;
		return $this;
	}    

	/**
	 * Get slug
	 *
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}


}
