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
 * @ORM\HasLifecycleCallbacks()
 */
abstract class adresse {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=100, nullable=false, unique=false)
	 * @Assert\NotBlank(message = "Vous devez nommer cet artible.")
	 * @Assert\Length(
	 *      min = "3",
	 *      max = "100",
	 *      minMessage = "Le nom doit comporter au moins {{ limit }} lettres.",
	 *      maxMessage = "Le nom doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	protected $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="adresse", type="text", nullable=true, unique=false)
	 */
	protected $adresse;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cp", type="string", length=10, nullable=true, unique=false)
	 */
	protected $cp;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ville", type="string", length=255, nullable=true, unique=false)
	 */
	protected $ville;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="commentaire", type="text", nullable=true, unique=false)
	 */
	protected $commentaire;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateCreation", type="datetime", nullable=false)
	 */
	protected $dateCreation;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateMaj", type="datetime", nullable=true)
	 */
	protected $dateMaj;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	protected $slug;



	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->dateMaj = null;
		$this->nom = "[nouvelle adresse]";
	}


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
	 * @return adresse
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
	 * Set adresse
	 *
	 * @param string $adresse
	 * @return adresse
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
	 * Set cp
	 *
	 * @param string $cp
	 * @return adresse
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

	/**
	 * Set ville
	 *
	 * @param string $ville
	 * @return adresse
	 */
	public function setVille($ville) {
		$this->ville = $ville;
	
		return $this;
	}

	/**
	 * Get ville
	 *
	 * @return string 
	 */
	public function getVille() {
		return $this->ville;
	}

	/**
	 * Set commentaire
	 *
	 * @param string $commentaire
	 * @return adresse
	 */
	public function setCommentaire($commentaire) {
		$this->commentaire = $commentaire;
	
		return $this;
	}

	/**
	 * Get commentaire
	 *
	 * @return string 
	 */
	public function getCommentaire() {
		return $this->commentaire;
	}

	/**
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return adresse
	 */
	public function setDateCreation($dateCreation) {
		$this->dateCreation = $dateCreation;
	
		return $this;
	}

	/**
	 * Get dateCreation
	 *
	 * @return \DateTime 
	 */
	public function getDateCreation() {
		return $this->dateCreation;
	}

	/**
	 * @ORM\PreUpdate
	 */
	public function updateDateMaj() {
		$this->setDateMaj(new \Datetime());
	}

	/**
	 * Set dateMaj
	 *
	 * @param \DateTime $dateMaj
	 * @return adresse
	 */
	public function setDateMaj($dateMaj) {
		$this->dateMaj = $dateMaj;
	
		return $this;
	}

	/**
	 * Get dateMaj
	 *
	 * @return \DateTime 
	 */
	public function getDateMaj() {
		return $this->dateMaj;
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
