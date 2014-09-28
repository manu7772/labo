<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ficheCreative
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\ficheCreativeRepository")
 */
class ficheCreative {
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
	 * @ORM\Column(name="nom", type="string", length=100, nullable=false, unique=false)
	 * @Assert\NotBlank(message = "Vous devez nommer cet artible.")
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
	 * @ORM\Column(name="datePublication", type="datetime", nullable=false)
	 */
	private $datePublication;

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
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $statut;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="accroche", type="string", length=200, nullable=true, unique=false)
	 * @Assert\Length(
	 *      max = "200",
	 *      maxMessage = "L'accroche doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	private $accroche;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="niveau", type="string", length=30, nullable=false, unique=false)
	 */
	private $niveau;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="duree", type="string", length=20, nullable=false, unique=false)
	 */
	private $duree;

	/**
	 * @var array
	 *
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\image")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $image;

	/**
	 * @var array
	 *
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\categorie")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $categorie;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\version")
	 */
	private $versions;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	private $slug;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="ficheCreatives")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $propUser;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\article", mappedBy="ficheCreatives")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $articles;

	private $listeNiveaux = array(
		"débutant" => "débutant",
		"intermédiaire" => "intermédiaire",
		"confirmé" => "confirmé",
		);

	private $durees = array(
        30    =>  "30 minutes",
        60    =>  "1 heure",
        90    =>  "1 heure 30",
        120   =>  "2 heures",
        150   =>  "2 heures 30",
        180   =>  "3 heures",
        210   =>  "3 heures 30",
        240   =>  "4 heures",
        270   =>  "4 heures 30",
        300   =>  "5 heures"
        );

	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->datePublication = new \Datetime();
		$this->dateMaj = null;
		$this->dateExpiration = null;
		$this->versions = new ArrayCollection();
		$this->articles = new ArrayCollection();
		reset($this->listeNiveaux);
		$this->setNiveau(current($this->listeNiveaux)); // Niveau par défaut
		$this->duree = 30;
	}


	/**
	 * get niveaux
	 *
	 * @return array 
	 */
	public function getListeNiveaux() {
		return $this->listeNiveaux;
	}

	/**
	 * get durees
	 *
	 * @return array 
	 */
	public function getDurees() {
		return $this->durees;
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
	 * @return ficheCreative
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
	 * @return ficheCreative
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
	 * @return ficheCreative
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
	 * Set datePublication
	 *
	 * @param \DateTime $datePublication
	 * @return ficheCreative
	 */
	public function setDatePublication($datePublication = null) {
		if(($datePublication < $this->dateCreation) || ($datePublication === null)) $datePublication = $this->dateCreation;
		$this->datePublication = $datePublication;
	
		return $this;
	}

	/**
	 * Get datePublication
	 *
	 * @return \DateTime 
	 */
	public function getDatePublication() {
		return $this->datePublication;
	}

	/**
	 * Set dateMaj
	 *
	 * @param \DateTime $dateMaj
	 * @return ficheCreative
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
	 * @return ficheCreative
	 */
	public function setDateExpiration($dateExpiration = null) {
		if(($dateExpiration < $this->dateCreation) && ($dateExpiration !== null)) $dateExpiration = null;
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
	 * @param \labo\Bundle\TestmanuBundle\Entity\statut $statut
	 * @return ficheCreative
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
	 * Set accroche
	 *
	 * @param string $accroche
	 * @return ficheCreative
	 */
	public function setAccroche($accroche) {
		$this->accroche = $accroche;
	
		return $this;
	}

	/**
	 * Get accroche
	 *
	 * @return string 
	 */
	public function getAccroche() {
		return $this->accroche;
	}

	/**
	 * Set niveau
	 *
	 * @param string $niveau
	 * @return ficheCreative
	 */
	public function setNiveau($niveau = null) {
		$this->niveau = $niveau;
	
		return $this;
	}

	/**
	 * Get niveau
	 *
	 * @return string 
	 */
	public function getNiveau() {
		return $this->niveau;
	}

	/**
	 * Set duree
	 *
	 * @param string $duree
	 * @return ficheCreative
	 */
	public function setDuree($duree = null) {
		$this->duree = $duree;
	
		return $this;
	}

	/**
	 * Get duree
	 *
	 * @return string 
	 */
	public function getDuree() {
		return $this->duree;
	}

	/**
	 * Set slug
	 *
	 * @param string $slug
	 * @return ficheCreative
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
	 * Set image
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\image $image
	 * @return ficheCreative
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
	 * Set categorie
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\categorie $categorie
	 * @return ficheCreative
	 */
	public function setCategorie(\labo\Bundle\TestmanuBundle\Entity\categorie $categorie) {
		$this->categorie = $categorie;
	
		return $this;
	}

	/**
	 * Get categorie
	 *
	 * @return \labo\Bundle\TestmanuBundle\Entity\categorie 
	 */
	public function getCategorie() {
		return $this->categorie;
	}

	/**
	 * Add versions
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\version $versions
	 * @return ficheCreative
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
	 * @return ficheCreative
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
	 * Get articles
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getArticles() {
		return $this->articles;
	}

	/**
	 * Add article
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\article $article
	 * @return video
	 */
	public function addArticle(\labo\Bundle\TestmanuBundle\Entity\article $article) {
		$this->articles[] = $article;
	
		return $this;
	}

	/**
	 * Remove article
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\article $article
	 */
	public function removeArticle(\labo\Bundle\TestmanuBundle\Entity\article $article) {
		$this->articles->removeElement($article);
	}


}