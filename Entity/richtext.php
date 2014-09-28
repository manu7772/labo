<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * richtext
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\richtextRepository")
 */
class richtext {
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
	 * @ORM\Column(name="nom", type="string", length=100, nullable=true, unique=true)
	 * @Assert\NotBlank(message = "Vous devez nommer cet élément.")
	 * @Assert\Length(
	 *      min = "3",
	 *      max = "100",
	 *      minMessage = "Le nom doit comporter au moins {{ limit }} lettres.",
	 *      maxMessage = "Le nom doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	private $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="titre", type="string", length=255, nullable=true, unique=false)
	 */
	private $titre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="resume", type="text", nullable=true, unique=false)
	 */
	private $resume;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="texte", type="text", nullable=false, unique=false)
	 */
	private $texte;

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
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="richtexts")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $propUser;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="protectedOnRemove", type="boolean")
	 */
	private $protectedOnRemove;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="directEditable", type="boolean")
	 */
	private $directEditable;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="twigConverti", type="boolean")
	 */
	private $twigConverti;

	/**
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $statut;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\typeRichtext")
	 */
	private $typeRichtexts;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\version")
	 */
	private $versions;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\tag", inversedBy="richtexts")
	 */
	private $tags;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	private $slug;



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
	 * @param \labo\Bundle\TestmanuBundle\Entity\statut $statut
	 * @return baseEntity
	 */
	public function setStatut(\labo\Bundle\TestmanuBundle\Entity\statut $statut = null) {
		$this->statut = $statut;
	
		return $this;
	}

	/**
	 * Get statut
	 *
	 * @return labo\Bundle\TestmanuBundle\Entity\statut 
	 */
	public function getStatut() {
		return $this->statut;
	}

	/**
	 * Add typeRichtexts
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\typeRichtext $typeRichtexts
	 * @return richtext
	 */
	public function addTypeRichtext(\labo\Bundle\TestmanuBundle\Entity\typeRichtext $typeRichtexts) {
		$this->typeRichtexts[] = $typeRichtexts;
	
		return $this;
	}

	/**
	 * Remove typeRichtexts
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\typeRichtext $typeRichtexts
	 */
	public function removeTypeRichtext(\labo\Bundle\TestmanuBundle\Entity\typeRichtext $typeRichtexts) {
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
	 * @param \labo\Bundle\TestmanuBundle\Entity\version $versions
	 * @return richtext
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
	 * Add tag
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\tag $tags
	 * @return richtext
	 */
	public function addTag(\labo\Bundle\TestmanuBundle\Entity\tag $tag) {
		$this->tags->add($tag);
		$tag->addRichtext($this);
	
		return $this;
	}

	/**
	 * Remove tag
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\tag $tag
	 */
	public function removeTag(\labo\Bundle\TestmanuBundle\Entity\tag $tag) {
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