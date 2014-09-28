<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * evenement
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\evenementRepository")
 */
class evenement {
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=100)
	 */
	private $nom;

	/**
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $statut;

	/**
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\typeEvenement")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $typeEvenement;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="datedebut", type="datetime", unique=false)
	 */
	private $datedebut;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="datefin", type="datetime", nullable=true, unique=false)
	 */
	private $datefin;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="string", length=255, nullable=true)
	 */
	private $descriptif;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateCreation", type="datetime", nullable=false)
	 */
	private $dateCreation;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateMaj", type="datetime", nullable=true)
	 */
	private $dateMaj;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateExpiration", type="datetime", nullable=true)
	 */
	private $dateExpiration;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	private $slug;

	/**
	 * @var integer
	 *
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\image")
	 * @ORM\JoinColumn(unique=false, nullable=true)
	 */
	private $image;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\version")
	 */
	private $versions;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="evenements")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $propUser;


	public function __construct() {
		$this->datedebut = new \Datetime();
		// $this->datedebut->modify('+1 day');
		// $this->datefin = null;
		// $this->datefin->modify('+1 day');
		$this->dateCreation = new \Datetime();
		$this->dateMaj = null;
		$this->dateExpiration = null;
		$this->versions = new ArrayCollection();
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
	 * @return evenement
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
	 * Set statut
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\statut $statut
	 * @return evenement
	 */
	public function setStatut(\labo\Bundle\TestmanuBundle\Entity\statut $statut) {
		$this->statut = $statut;
	
		return $this;
	}

	/**
	 * Get statut
	 *
	 * @return \labo\Bundle\TestmanuBundle\Entity\statut 
	 */
	public function getStatut() {
		return $this->statut;
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

	/**
	 * Set typeEvenement
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\typeEvenement $typeEvenement
	 * @return evenement
	 */
	public function setTypeEvenement(\labo\Bundle\TestmanuBundle\Entity\typeEvenement $typeEvenement) {
		$this->typeEvenement = $typeEvenement;
	
		return $this;
	}

	/**
	 * Get typeEvenement
	 *
	 * @return \labo\Bundle\TestmanuBundle\Entity\typeEvenement 
	 */
	public function getTypeEvenement() {
		return $this->typeEvenement;
	}

	/**
	 * Set datedebut
	 *
	 * @param \DateTime $datedebut
	 * @return evenement
	 */
	public function setDatedebut($datedebut) {
		$this->datedebut = $datedebut;
	
		return $this;
	}

	/**
	 * Get datedebut
	 *
	 * @return \DateTime 
	 */
	public function getDatedebut() {
		return $this->datedebut;
	}

	/**
	 * Set datefin
	 *
	 * @param \DateTime $datefin
	 * @return evenement
	 */
	public function setDatefin($datefin) {
		$this->datefin = $datefin;
	
		return $this;
	}

	/**
	 * Get datefin
	 *
	 * @return \DateTime 
	 */
	public function getDatefin() {
		return $this->datefin;
	}

	/**
	 * Set descriptif
	 *
	 * @param string $descriptif
	 * @return evenement
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
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return evenement
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
	 * Set dateMaj
	 *
	 * @param \DateTime $dateMaj
	 * @return evenement
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
	 * Set dateExpiration
	 *
	 * @param \DateTime $dateExpiration
	 * @return evenement
	 */
	public function setDateExpiration($dateExpiration) {
		$this->dateExpiration = $dateExpiration;
	
		return $this;
	}

	/**
	 * Get dateExpiration
	 *
	 * @return \DateTime 
	 */
	public function getDateExpiration() {
		return $this->dateExpiration;
	}

	/**
	 * Set image
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\image $image
	 * @return evenement
	 */
	public function setImage(\labo\Bundle\TestmanuBundle\Entity\image $image = null) {
		$this->image = $image;
	
		return $this;
	}

	/**
	 * Get image
	 *
	 * @return \labo\Bundle\TestmanuBundle\Entity\image 
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Add versions
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\version $versions
	 * @return evenement
	 */
	public function addVersion(\labo\Bundle\TestmanuBundle\Entity\version $versions) {
		$this->versions[] = $versions;
	
		return $this;
	}

	/**
	 * Remove versions
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\version $versions
	 */
	public function removeVersion(\labo\Bundle\TestmanuBundle\Entity\version $versions) {
		$this->versions->removeElement($versions);
	}

	/**
	 * Get versions
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getVersions() {
		return $this->versions;
	}

	/**
	 * Set propUser
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $propUser
	 * @return evenement
	 */
	public function setPropUser(\AcmeGroup\UserBundle\Entity\User $propUser = null) {
		$this->propUser = $propUser;
	
		return $this;
	}

	/**
	 * Get propUser
	 *
	 * @return \AcmeGroup\UserBundle\Entity\User 
	 */
	public function getPropUser() {
		return $this->propUser;
	}



}
