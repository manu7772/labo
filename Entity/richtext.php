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
abstract class richtext {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=100, nullable=true, unique=true)
	 * @Assert\NotBlank(message = "Vous devez nommer cet élément.")
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
	 * @ORM\Column(name="titre", type="string", length=255, nullable=true, unique=false)
	 */
	protected $titre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="resume", type="text", nullable=true, unique=false)
	 */
	protected $resume;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="texte", type="text", nullable=false, unique=false)
	 */
	protected $texte;

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
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="richtexts")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $propUser;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="protectedOnRemove", type="boolean")
	 */
	protected $protectedOnRemove;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="directEditable", type="boolean")
	 */
	protected $directEditable;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="twigConverti", type="boolean")
	 */
	protected $twigConverti;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $statut;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\typeRichtext")
	 */
	protected $typeRichtexts;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\version")
	 */
	protected $versions;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\tag", inversedBy="richtexts")
	 */
	protected $tags;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	protected $slug;



	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->dateMaj = null;
		$this->protectedOnRemove = false;
		$this->directEditable = true;
		$this->twigConverti = false;
		$this->versions = new ArrayCollection();
		$this->typeRichtexts = new ArrayCollection();
		$this->tags = new ArrayCollection();
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
	 * @return richtext
	 */
	public function setNom($nom) {
		if($this->protectedOnRemove === false) $this->nom = strip_tags(trim($nom));
	
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
	 * Set titre
	 *
	 * @param string $titre
	 * @return richtext
	 */
	public function setTitre($titre) {
		$this->titre = trim($titre);
	
		return $this;
	}

	/**
	 * Get titre
	 *
	 * @return string 
	 */
	public function getTitre() {
		return $this->titre;
	}

	/**
	 * Set resume
	 *
	 * @param string $resume
	 * @return richtext
	 */
	public function setResume($resume) {
		$this->resume = trim($resume);
	
		return $this;
	}

	/**
	 * Get resume
	 *
	 * @return string 
	 */
	public function getResume() {
		return $this->resume;
	}

	/**
	 * Set texte
	 *
	 * @param string $texte
	 * @return richtext
	 */
	public function setTexte($texte) {
		$this->texte = trim($texte);
	
		return $this;
	}

	/**
	 * Get texte
	 *
	 * @return string 
	 */
	public function getTexte() {
		return $this->texte;
	}

	/**
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return richtext
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
	 * @return richtext
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
	 * Set propUser
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $propUser
	 * @return richtext
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

	/**
	 * Set protectedOnRemove
	 *
	 * @param boolean
	 * @return richtext
	 */
	public function setProtectedOnRemove($protectedOnRemove = null) {
		$this->protectedOnRemove = $protectedOnRemove;
		return $this;
	}

	/**
	 * Get protectedOnRemove
	 *
	 * @return boolean 
	 */
	public function getProtectedOnRemove() {
		return $this->protectedOnRemove;
	}

	/**
	 * Set directEditable
	 *
	 * @param boolean
	 * @return richtext
	 */
	public function setDirectEditable($directEditable = null) {
		$this->directEditable = $directEditable;
		return $this;
	}

	/**
	 * Get directEditable
	 *
	 * @return boolean 
	 */
	public function getDirectEditable() {
		return $this->directEditable;
	}

	/**
	 * Set twigConverti
	 *
	 * @param boolean
	 * @return richtext
	 */
	public function setTwigConverti($twigConverti = null) {
		$this->twigConverti = $twigConverti;
		return $this;
	}

	/**
	 * Get twigConverti
	 *
	 * @return boolean 
	 */
	public function getTwigConverti() {
		return $this->twigConverti;
	}

	/**
	 * Set statut
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\statut $statut
	 * @return baseEntity
	 */
	public function setStatut(\AcmeGroup\LaboBundle\Entity\statut $statut = null) {
		$this->statut = $statut;
	
		return $this;
	}

	/**
	 * Get statut
	 *
	 * @return AcmeGroup\LaboBundle\Entity\statut 
	 */
	public function getStatut() {
		return $this->statut;
	}

	/**
	 * Add typeRichtexts
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\typeRichtext $typeRichtexts
	 * @return richtext
	 */
	public function addTypeRichtext(\AcmeGroup\LaboBundle\Entity\typeRichtext $typeRichtexts) {
		$this->typeRichtexts[] = $typeRichtexts;
	
		return $this;
	}

	/**
	 * Remove typeRichtexts
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\typeRichtext $typeRichtexts
	 */
	public function removeTypeRichtext(\AcmeGroup\LaboBundle\Entity\typeRichtext $typeRichtexts) {
		$this->typeRichtexts->removeElement($typeRichtexts);
	}

	/**
	 * Get typeRichtexts
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getTypeRichtexts() {
		return $this->typeRichtexts;
	}

	/**
	 * Add versions
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\version $versions
	 * @return richtext
	 */
	public function addVersion(\AcmeGroup\LaboBundle\Entity\version $versions) {
		$this->versions[] = $versions;
	
		return $this;
	}

	/**
	 * Remove versions
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\version $versions
	 */
	public function removeVersion(\AcmeGroup\LaboBundle\Entity\version $versions) {
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
	 * Add tag
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\tag $tags
	 * @return richtext
	 */
	public function addTag(\AcmeGroup\LaboBundle\Entity\tag $tag) {
		$this->tags->add($tag);
		$tag->addRichtext($this);
	
		return $this;
	}

	/**
	 * Remove tag
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\tag $tag
	 */
	public function removeTag(\AcmeGroup\LaboBundle\Entity\tag $tag) {
		$this->tags->removeElement($tag);
		$tag->removeRichtext($this);
	}

	/**
	 * Get tags
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getTags() {
		return $this->tags;
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