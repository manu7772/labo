<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Tree
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * categorie
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="categorie")
 * use repository for handy tree functions
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\categorieRepository")
 * @UniqueEntity(fields={"nom"}, message="Cette catégorie existe déjà")
 */

class categorie {
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	private $id;

	/**
	 * @ORM\Column(name="nom", type="string", length=64)
	 * @Assert\NotBlank(message = "Vous devez donner un nom à la catégorie.")
	 * @Assert\Length(
	 *      min = "2",
	 *      max = "64",
	 *      minMessage = "Le nom doit comporter au moins {{ limit }} lettres.",
	 *      maxMessage = "Le nom doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	private $nom;

	/**
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\pageweb")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $page;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nomroutepage", type="string", length=255, nullable=true)
	 */
	private $nomroutepage;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nommenu", type="string", length=100, nullable=true)
	 */
	private $nommenu;

	/**
	 * @Gedmo\TreeLeft
	 * @ORM\Column(name="lft", type="integer")
	 */
	private $lft;

	/**
	 * @Gedmo\TreeLevel
	 * @ORM\Column(name="lvl", type="integer")
	 */
	private $lvl;

	/**
	 * @Gedmo\TreeRight
	 * @ORM\Column(name="rgt", type="integer")
	 */
	private $rgt;

	/**
	 * @Gedmo\TreeRoot
	 * @ORM\Column(name="root", type="integer", nullable=true)
	 */
	private $root;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="categorie", inversedBy="children", cascade={"persist"})
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $parent;

	/**
	 * @ORM\OneToMany(targetEntity="categorie", mappedBy="parent")
	 * @ORM\OrderBy({"lft" = "ASC"})
	 */
	private $children;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true, unique=false)
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
	 * @ORM\Column(name="dateExpiration", type="datetime", nullable=true)
	 */
	private $dateExpiration;

	/**
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $statut;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	private $slug;

	/**
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\version")
	 */
	private $versions;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="plusVisible", type="boolean")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $plusVisible;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="couleur", type="string", length=30, nullable=false, unique=false)
	 */
	private $couleur;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="parametreUrl", type="string", nullable=true, unique=false)
	 */
	private $parametreUrl;


	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->plusVisible = false;
		$this->couleur = "FFFFFF";
		$this->parametreUrl = "";
		$this->versions = new ArrayCollection();
	}


	public function getId() {
		return $this->id;
	}

	public function setNom($nom) {
		$this->nom = $nom;
	}

	public function getNom() {
		return $this->nom;
	}

	public function setNommenu($nommenu) {
		$this->nommenu = $nommenu;
	}

	public function getNommenu() {
		return $this->nommenu;
	}

	public function setNomroutepage($nomroutepage = null) {
		$this->nomroutepage = $nomroutepage;
	}

	public function getNomroutepage() {
		return $this->nomroutepage;
	}

	public function setPage(\labo\Bundle\TestmanuBundle\Entity\pageweb $page = null) {
		$this->page = $page;
		if(is_object($page)) {
			$this->setNomroutepage($page->getRoute()."___".$page->getSlug());
		} else {
			$this->setNomroutepage(null);
		}
	}

	public function getPage() {
		return $this->page;
	}

	public function setParent(categorie $parent = null) {
		$this->parent = $parent;
	}

	public function getParent() {
		return $this->parent;
	}

	public function getChildren() {
		return $this->children;
	}

	/**
	 * Set descriptif
	 *
	 * @param string $descriptif
	 * @return baseEntity
	 */
	public function setDescriptif($descriptif = null) {
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
	 * @return baseEntity
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
	 * Set dateExpiration
	 *
	 * @param \DateTime $dateExpiration
	 * @return baseEntity
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
	 * Set statut
	 *
	 * @param integer $statut
	 * @return baseEntity
	 */
	public function setStatut(\labo\Bundle\TestmanuBundle\Entity\statut $statut) {
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
	 * Set inMenu
	 *
	 * @param boolean $inMenu
	 * @return article
	 */
	public function setInMenu($inMenu = true) {
		$this->inMenu = $inMenu;
		return $this;
	}

	/**
	 * Get inMenu
	 *
	 * @return boolean 
	 */
	public function getInMenu() {
		return $this->inMenu;
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
	 * Add versions
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\version $versions
	 * @return article
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
	 * Set plusVisible
	 *
	 * @param boolean $plusVisible
	 * @return article
	 */
	public function setPlusVisible($plusVisible) {
		$this->plusVisible = $plusVisible;
	
		return $this;
	}

	/**
	 * Get plusVisible
	 *
	 * @return boolean 
	 */
	public function getPlusVisible() {
		return $this->plusVisible;
	}

	/**
	 * Set couleur
	 *
	 * @param string $couleur
	 * @return version
	 */
	public function setCouleur($couleur) {
		$this->couleur = $couleur;
	
		return $this;
	}

	/**
	 * Get couleur
	 *
	 * @return string 
	 */
	public function getCouleur() {
		return $this->couleur;
	}

	/**
	 * Set parametreUrl
	 *
	 * @param string $parametreUrl
	 * @return version
	 */
	public function setParametreUrl($parametreUrl) {
		$this->parametreUrl = $parametreUrl;
	
		return $this;
	}

	/**
	 * Get parametreUrl
	 *
	 * @return string 
	 */
	public function getParametreUrl() {
		return $this->parametreUrl;
	}


}
