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
abstract class article {

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
	 * @ORM\OneToMany(targetEntity="AcmeGroup\LaboBundle\Entity\voteArticle", mappedBy="article")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $voteUsers;

	/**
	 * @ORM\OneToMany(targetEntity="AcmeGroup\LaboBundle\Entity\voteArticleBlack", mappedBy="article")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $voteBlacks;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true, unique=false)
	 */
	protected $descriptif;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="serie", type="string", length=100, nullable=true, unique=false)
	 * @Assert\Length(
	 *      max = "100",
	 *      maxMessage = "La serie doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	protected $serie;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="avisDuTechnicien", type="text", nullable=true, unique=false)
	 */
	protected $avisDuTechnicien;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ventes", type="integer", nullable=false, unique=false)
	 */
	protected $ventes;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="rank", type="smallint", nullable=false, unique=false)
	 */
	protected $rank;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="notation", type="smallint", nullable=false, unique=false)
	 */
	protected $notation;

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
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateExpiration", type="datetime", nullable=true)
	 */
	protected $dateExpiration;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $statut;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="refFabricant", type="string", length=100, nullable=true, unique=false)
	 * @Assert\Length(
	 *      max = "100",
	 *      maxMessage = "La référence frabricant doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	protected $refFabricant;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="accroche", type="string", length=60, nullable=true, unique=false)
	 * @Assert\Length(
	 *      max = "60",
	 *      maxMessage = "L'accroche doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	protected $accroche;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="styleAccroche", type="string", length=60, nullable=true, unique=false)
	 */
	protected $styleAccroche;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="texteprix", type="string", length=100, nullable=true, unique=false)
	 */
	protected $texteprix;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="prix", type="decimal", scale=2, nullable=true, unique=false)
	 */
	protected $prix;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="prixHT", type="decimal", scale=2, nullable=true, unique=false)
	 */
	protected $prixHT;

	/**
	 * @var string
	 *
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\tauxTVA")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $tauxTVA;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="plusVisible", type="boolean", nullable=true, unique=false)
	 */
	protected $plusVisible;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\marque")
	 * @ORM\JoinColumn(nullable=true)
	 */
	protected $marque;

	/**
	 * @var array
	 *
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $imagePpale;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\image")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $images;

	/**
	 * @var array
	 *
	 * @ORM\OneToOne(targetEntity="AcmeGroup\LaboBundle\Entity\fichierPdf", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=true, unique=true)
	 */
	protected $fichierPdf;

	/**
	 * @var array
	 *
	 * @ORM\OneToOne(targetEntity="AcmeGroup\LaboBundle\Entity\fichierPdf", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=true, unique=true)
	 */
	protected $ficheTechniquePdf;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\categorie")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $categories;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\reseau", inversedBy="articles")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $reseaus;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\article", mappedBy="articlesLies")
	 */
	protected $articlesParents;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\article", inversedBy="articlesParents")
	 * @ORM\JoinTable(name="articlesLinks",
	 *     joinColumns={@ORM\JoinColumn(name="articlesLies_id", referencedColumnName="id")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="articlesParents_id", referencedColumnName="id")}
	 * )
	 */
	protected $articlesLies;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\version")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $versions;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	protected $slug;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="articles")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $propUser;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\video", inversedBy="articles")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $videos;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\ficheCreative", inversedBy="articles")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $ficheCreatives;


	protected $exclureseau;
	protected $exclu2reseau;


	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->dateMaj = null;

		$this->voteUsers = new ArrayCollection();

		$this->dateExpiration = null;
		// $this->occasion = false;
		// $this->fraisBancaires = 0;
		$this->exclureseau = null;
		$this->exclu2reseau = null;
		$this->styleAccroche = "normal";
		$this->ventes = 0;
		$this->rank = 0;
		$this->notation = 0;
		// $this->demonstration = false;
		// $this->formation = false;
		// $this->fichierPdf = null;
		// $this->ficheTechniquePdf = null;
		$this->plusVisible = false;
		$this->reseaus = new ArrayCollection();
		$this->images = new ArrayCollection();
		$this->categories = new ArrayCollection();
		$this->versions = new ArrayCollection();
		$this->articlesParents = new ArrayCollection();
		$this->articlesLies = new ArrayCollection();
		$this->videos = new ArrayCollection();
		$this->ficheCreatives = new ArrayCollection();
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
	 * Get exclureseau
	 *
	 * @return string 
	 */
	public function getExclureseau() {
		$rr = array();
		foreach($this->getReseaus() as $r) $rr[] = $r->getNom();
		if(in_array("grande distribution", $rr)) $this->exclureseau = "gdistribution";
		if(in_array("spécialistes", $rr)) $this->exclureseau = "professionnels";
		if(in_array("e-commerce", $rr)) $this->exclureseau = "internet";
		// par défaut : professionnels
		if($this->exclureseau === null) $this->exclureseau = "professionnels";
		return $this->exclureseau;
	}

	/**
	 * Get exclureseau
	 *
	 * @return string 
	 */
	public function getExclu2reseau() {
		$rr = array();
		foreach($this->getReseaus() as $r) $rr[] = $r->getNom();
		if(in_array("grande distribution", $rr)) $this->exclu2reseau = "GRANDE DISTRIBUTION";
		if(in_array("spécialistes", $rr)) $this->exclu2reseau = "RÉSEAU PROFRESSIONNEL";
		if(in_array("e-commerce", $rr)) $this->exclu2reseau = "EXCLUSIVITÉ E-BOUTIQUE";
		// par défaut : professionnels
		if($this->exclu2reseau === null) $this->exclu2reseau = "RÉSEAU PROFRESSIONNEL";
		return $this->exclu2reseau;
	}

	/**
	 * Set exclureseau
	 *
	 * @return article 
	 */
	public function setExclureseau($reso) {
		if(is_string($reso)) $this->exclureseau = $reso;
		return $this;
	}

	/**
	 * Set nom
	 *
	 * @param string $nom
	 * @return article
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
	 * @return article
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
	 * Set serie
	 *
	 * @param string $serie
	 * @return article
	 */
	public function setSerie($serie) {
		$this->serie = $serie;
	
		return $this;
	}

	/**
	 * Get serie
	 *
	 * @return string 
	 */
	public function getSerie() {
		return $this->serie;
	}

	/**
	 * Set avisDuTechnicien
	 *
	 * @param string $avisDuTechnicien
	 * @return article
	 */
	public function setAvisDuTechnicien($avisDuTechnicien) {
		$this->avisDuTechnicien = $avisDuTechnicien;
	
		return $this;
	}

	/**
	 * Get avisDuTechnicien
	 *
	 * @return string 
	 */
	public function getAvisDuTechnicien() {
		return $this->avisDuTechnicien;
	}

	/**
	 * Set ventes
	 *
	 * @param integer $ventes
	 * @return article
	 */
	public function setVentes($ventes) {
		$this->ventes = $ventes;
	
		return $this;
	}

	/**
	 * Add ventes
	 *
	 * @param integer $ventes
	 * @return article
	 */
	public function addVentes($ventes) {
		$this->ventes += $ventes;
	
		return $this;
	}

	/**
	 * Get ventes
	 *
	 * @return integer 
	 */
	public function getVentes() {
		return $this->ventes;
	}

	/**
	 * Set rank
	 *
	 * @param integer $rank
	 * @return article
	 */
	public function setRank($rank) {
		$this->rank = $rank;
	
		return $this;
	}

	/**
	 * Get rank
	 *
	 * @return integer 
	 */
	public function getRank() {
		return $this->rank;
	}

	/**
	 * Set notation
	 *
	 * @param integer $notation
	 * @return article
	 */
	public function setNotation($notation) {
		$this->notation = $notation;
	
		return $this;
	}

	/**
	 * Get notation
	 *
	 * @return integer 
	 */
	public function getNotation() {
		return $this->notation;
	}

	/**
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return article
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
	 * @return article
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
	 * @return article
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
	 * Set refFabricant
	 *
	 * @param string $refFabricant
	 * @return article
	 */
	public function setRefFabricant($refFabricant) {
		$this->refFabricant = $refFabricant;
	
		return $this;
	}

	/**
	 * Get refFabricant
	 *
	 * @return string 
	 */
	public function getRefFabricant() {
		return $this->refFabricant;
	}

	/**
	 * Set accroche
	 *
	 * @param string $accroche
	 * @return article
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
	 * Set styleAccroche
	 *
	 * @param string $styleAccroche
	 * @return article
	 */
	public function setStyleAccroche($styleAccroche) {
		$this->styleAccroche = $styleAccroche;
	
		return $this;
	}

	/**
	 * Get styleAccroche
	 *
	 * @return string 
	 */
	public function getStyleAccroche() {
		return $this->styleAccroche;
	}

	/**
	 * Set texteprix
	 *
	 * @param string $texteprix
	 * @return article
	 */
	public function setTexteprix($texteprix) {
		$this->texteprix = $texteprix;
		return $this;
	}

	/**
	 * Get texteprix
	 *
	 * @return string 
	 */
	public function getTexteprix() {
		return $this->texteprix;
	}

	/**
	 * Set prix
	 *
	 * @param float $prix
	 * @return article
	 */
	public function setPrix($prix) {
		$this->prix = $prix;
		$this->prixHT = $prix / (1 + ($this->tauxTVA->getTaux() / 100));
		return $this;
	}

	/**
	 * Get prix
	 *
	 * @return float 
	 */
	public function getPrix() {
		return $this->prix;
	}

	/**
	 * Set prixHT
	 *
	 * @param float $prixHT
	 * @return article
	 */
	public function setPrixHT($prixHT = null) {
		if($prixHT !== null) {
			$this->prixHT = $prixHT;
			$this->prix = $prixHT * (1 + ($this->tauxTVA->getTaux() / 100));
		}
		return $this;
	}

	/**
	 * Get prixHT
	 *
	 * @return float 
	 */
	public function getPrixHT() {
		return $this->prixHT;
	}

	/**
	 * Get TVA
	 *
	 * @return float 
	 */
	public function getTva() {
		return $this->prixHT * ($this->tauxTVA->getTaux() / 100);
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
	 * Set statut
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\statut $statut
	 * @return article
	 */
	public function setStatut(\AcmeGroup\LaboBundle\Entity\statut $statut) {
		$this->statut = $statut;
	
		return $this;
	}

	/**
	 * Get statut
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\statut 
	 */
	public function getStatut() {
		return $this->statut;
	}

	/**
	 * Set tauxTVA
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\tauxTVA $tauxTVA
	 * @return article
	 */
	public function setTauxTVA(\AcmeGroup\LaboBundle\Entity\tauxTVA $tauxTVA) {
		$this->tauxTVA = $tauxTVA;
	
		return $this;
	}

	/**
	 * Get tauxTVA
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\tauxTVA 
	 */
	public function getTauxTVA() {
		return $this->tauxTVA;
	}

	/**
	 * Set marque
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\marque $marque
	 * @return article
	 */
	public function setMarque(\AcmeGroup\LaboBundle\Entity\marque $marque) {
		$this->marque = $marque;
	
		return $this;
	}

	/**
	 * Get marque
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\marque 
	 */
	public function getMarque() {
		return $this->marque;
	}

	/**
	 * Set imagePpale
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $imagePpale
	 * @return article
	 */
	public function setImagePpale(\AcmeGroup\LaboBundle\Entity\image $imagePpale = null) {
		$this->imagePpale = $imagePpale;
	
		return $this;
	}

	/**
	 * Get imagePpale
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\imagePpale 
	 */
	public function getImagePpale() {
		return $this->imagePpale;
	}

	/**
	 * Set fichierPdf
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\fichierPdf $fichierPdf
	 * @return article
	 */
	public function setFichierPdf(\AcmeGroup\LaboBundle\Entity\fichierPdf $fichierPdf = null) {
		$this->fichierPdf = $fichierPdf;
	
		return $this;
	}

	/**
	 * Get fichierPdf
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\fichierPdf 
	 */
	public function getFichierPdf() {
		return $this->fichierPdf;
	}

	/**
	 * Set ficheTechniquePdf
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\fichierPdf $ficheTechniquePdf
	 * @return article
	 */
	public function setFicheTechniquePdf(\AcmeGroup\LaboBundle\Entity\fichierPdf $ficheTechniquePdf = null) {
		$this->ficheTechniquePdf = $ficheTechniquePdf;
	
		return $this;
	}

	/**
	 * Get ficheTechniquePdf
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\fichierPdf 
	 */
	public function getFicheTechniquePdf() {
		return $this->ficheTechniquePdf;
	}

	/**
	 * Add images
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $images
	 * @return article
	 */
	public function addImage(\AcmeGroup\LaboBundle\Entity\image $images = null) {
		if($images !== null) $this->images[] = $images;
	
		return $this;
	}

	/**
	 * Remove images
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $images
	 */
	public function removeImage(\AcmeGroup\LaboBundle\Entity\image $images = null) {
		if($images !== null) $this->images->removeElement($images);
	}

	/**
	 * Get images
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * Get categories
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Add categories
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\categorie $categories
	 * @return article
	 */
	public function addCategorie(\AcmeGroup\LaboBundle\Entity\categorie $categories) {
		$this->categories[] = $categories;
	
		return $this;
	}

	/**
	 * Remove categories
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\categorie $categories
	 */
	public function removeCategorie(\AcmeGroup\LaboBundle\Entity\categorie $categories) {
		$this->categories->removeElement($categories);
	}

	/**
	 * Get reseaus
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getReseaus() {
		return $this->reseaus;
	}

	/**
	 * Add reseaus
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\reseau $reseaus
	 * @return article
	 */
	public function addReseau(\AcmeGroup\LaboBundle\Entity\reseau $reseaus) {
		$this->reseaus[] = $reseaus;
		$reseaus->addBdirArticle($this);
	
		return $this;
	}

	/**
	 * Remove reseaus
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\reseau $reseaus
	 */
	public function removeReseau(\AcmeGroup\LaboBundle\Entity\reseau $reseaus) {
		$this->reseaus->removeElement($reseaus);
		$reseau->removeBdirArticle($this);
	}

	/**
	 * Add BdirReseaus -----> pour bidirectionnel bilatéral
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\reseau $reseaus
	 * @return article
	 */
	public function addBdirReseau(\AcmeGroup\LaboBundle\Entity\reseau $reseaus) {
		$this->reseaus[] = $reseaus;
	
		return $this;
	}

	/**
	 * Remove BdirReseaus -----> pour bidirectionnel bilatéral
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\reseau $reseaus
	 */
	public function removeBdirReseau(\AcmeGroup\LaboBundle\Entity\reseau $reseaus) {
		$this->reseaus->removeElement($reseaus);
	}

	/**
	 * Add versions
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\version $versions
	 * @return article
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
	 * Add articlesParents
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $articlesParents
	 * @return article
	 */
	public function addArticlesParent(\AcmeGroup\LaboBundle\Entity\article $articlesParents) {
		$this->articlesParents[] = $articlesParents;
	
		return $this;
	}

	/**
	 * Remove articlesParents
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $articlesParents
	 */
	public function removeArticlesParent(\AcmeGroup\LaboBundle\Entity\article $articlesParents) {
		$this->articlesParents->removeElement($articlesParents);
	}

	/**
	 * Get articlesParents
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getArticlesParents() {
		return $this->articlesParents;
	}

	/**
	 * Add articlesLies
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $articlesLies
	 * @return article
	 */
	public function addArticlesLie(\AcmeGroup\LaboBundle\Entity\article $articlesLies) {
		$this->articlesLies[] = $articlesLies;
		$articlesLies->addArticlesParent($this);
		return $this;
	}

	/**
	 * Remove articlesLies
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $articlesLies
	 */
	public function removeArticlesLie(\AcmeGroup\LaboBundle\Entity\article $articlesLies) {
		$this->articlesLies->removeElement($articlesLies);
		$articlesLies->removeArticlesParent($this);
	}

	/**
	 * Get articlesLies
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getArticlesLies() {
		return $this->articlesLies;
	}

	/**
	 * Set propUser
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $propUser
	 * @return article
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
	 * Add voteUser
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\voteArticle $voteArticle
	 * @return article
	 */
	public function addVoteUser(\AcmeGroup\LaboBundle\Entity\voteArticle $voteArticle) {
		$this->voteUsers[] = $voteArticle;
	
		return $this;
	}

	/**
	 * Remove voteUser
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\voteArticle $voteArticle
	 */
	public function removeVoteUser(\AcmeGroup\LaboBundle\Entity\voteArticle $voteArticle) {
		$this->voteUsers->removeElement($voteArticle);
	}

	/**
	 * Get voteUsers
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getVoteUsers() {
		return $this->voteUsers;
	}

	/**
	 * Add voteBlack
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\voteArticleBlack $voteArticleBlack
	 * @return article
	 */
	public function addVoteBlack(\AcmeGroup\LaboBundle\Entity\voteArticleBlack $voteArticleBlack) {
		$this->voteBlacks[] = $voteArticleBlack;
	
		return $this;
	}

	/**
	 * Remove voteBlack
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\voteArticleBlack $voteArticleBlack
	 */
	public function removeVoteBlack(\AcmeGroup\LaboBundle\Entity\voteArticleBlack $voteArticleBlack) {
		$this->voteBlacks->removeElement($voteArticleBlack);
	}

	/**
	 * Get voteBlacks
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getVoteBlacks() {
		return $this->voteBlacks;
	}


	/**
	 * Get videos
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getVideos() {
		return $this->videos;
	}

	/**
	 * Add video
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\video $video
	 * @return article
	 */
	public function addVideo(\AcmeGroup\LaboBundle\Entity\video $video = null) {
		if($video !== null) {
			$this->videos[] = $video;
			$video->addArticle($this);
		}
	
		return $this;
	}

	/**
	 * Remove video
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\video $video
	 */
	public function removeVideo(\AcmeGroup\LaboBundle\Entity\video $video = null) {
		if($video !== null) {
			$this->videos->removeElement($video);
			$video->removeArticle($this);
		}
	}

	/**
	 * Get ficheCreatives
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getFicheCreatives() {
		return $this->ficheCreatives;
	}

	/**
	 * Add ficheCreative
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\ficheCreative $ficheCreative
	 * @return article
	 */
	public function addFicheCreative(\AcmeGroup\LaboBundle\Entity\ficheCreative $ficheCreative = null) {
		if($ficheCreative !== null) {
			$this->ficheCreatives[] = $ficheCreative;
			$ficheCreative->addArticle($this);
		}
	
		return $this;
	}

	/**
	 * Remove ficheCreative
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\ficheCreative $ficheCreative
	 */
	public function removeFicheCreative(\AcmeGroup\LaboBundle\Entity\ficheCreative $ficheCreative = null) {
		if($ficheCreative !== null) {
			$this->ficheCreatives->removeElement($ficheCreative);
			$ficheCreative->removeArticle($this);
		}
	}

}