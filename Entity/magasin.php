<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;
use \DateTime;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class magasin {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="code", type="string", length=64, nullable=true)
	 */
	protected $code;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nomformenu", type="string", length=255, nullable=true)
	 */
	protected $nomformenu;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="telmobile", type="string", length=24, nullable=true)
	 */
	protected $telmobile;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="adresse", type="text", nullable=true)
	 */
	protected $adresse;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="responsable", type="string", length=100, nullable=true)
	 */
	protected $responsable;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="siteurl", type="string", length=255, nullable=true)
	 */
	protected $siteurl;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cp", type="string", length=12, nullable=true)
	 */
	protected $cp;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="secteur", type="string", length=12, nullable=true)
	 */
	protected $secteur;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=100, nullable=true)
	 */
	protected $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="commentaire", type="text", nullable=true)
	 */
	protected $commentaire;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="departement", type="string", length=12, nullable=true)
	 */
	protected $departement;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ville", type="string", length=100, nullable=true)
	 */
	protected $ville;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
	 */
	protected $telephone;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="type", type="string", length=24, nullable=true)
	 */
	protected $type;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="magvoisin1", type="string", length=100, nullable=true)
	 */
	protected $magvoisin1;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="magvoisin2", type="string", length=100, nullable=true)
	 */
	protected $magvoisin2;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="raisonsociale", type="string", length=100, nullable=true)
	 */
	protected $raisonsociale;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="titreSeo", type="string", length=100, nullable=true)
	 */
	protected $titreSeo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descSeo", type="string", length=100, nullable=true)
	 */
	protected $descSeo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="metakey", type="string", length=255, nullable=true)
	 */
	protected $metakey;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="typemagasin", type="string", length=100, nullable=true)
	 */
	protected $typemagasin;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nommagasin", type="string", length=100, nullable=true)
	 */
	protected $nommagasin;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="posithoraire", type="integer", nullable=true)
	 */
	protected $posithoraire;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="item", type="string", length=255, nullable=true)
	 */
	protected $item;

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
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $statut;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="plusVisible", type="boolean", nullable=true, unique=false)
	 */
	protected $plusVisible;

	/**
	 * @var array
	 *
	 * @ORM\OneToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=true)
	 */
	protected $image;

	protected $listTypesMagasins = array(
        "magasin_singer" => "Magasin Singer",
        "point_relais" => "Point Relais",
        "agent_singer" => "Agent Singer",
        );

	public function __construct() {
		$this->dateCreation = new DateTime();
		$this->dateMaj = null;

		$this->versions = new ArrayCollection();
		$this->plusVisible = false;
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
		$this->setDateMaj(new DateTime());
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
	 * Get listTypesMagasins
	 *
	 * @return string 
	 */
	public function getTypeMagasins() {
		return $this->listTypesMagasins;
	}

	/**
	 * Get keysListTypesMagasins
	 *
	 * @return string 
	 */
	public function getKeysListTypesMagasins() {
		return array_keys($this->listTypesMagasins);
	}

	/**
	 * Set code
	 *
	 * @param string $code
	 * @return magasin
	 */
	public function setCode($code) {
		$this->code = $code;
	
		return $this;
	}

	/**
	 * Get code
	 *
	 * @return string 
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * Init nomformenu
	 *
	 * @return magasin
	 */
	public function initNomformenu() {
		if(strlen($this->getNommagasin()) != (strlen(substr($this->getNommagasin(), 0, 24)))) $extN = "…";
			else $extN = "";
		if(strlen($this->getVille()) != (strlen(substr($this->getVille(), 0, 16)))) $extV = "…";
			else $extV = "";
		if(strlen($this->getCp()."") < 1) $locale = $this->getDepartement()."XXX";
			else $locale = $this->getCp();
		$this->nomformenu = 
			$locale." ".
			substr($this->getVille(), 0, 16).$extV." - ".
			substr($this->getNommagasin(), 0, 24).$extN;
	
		return $this;
	}

	/**
	 * Set nomformenu
	 *
	 * @param string $nomformenu
	 * @return magasin
	 */
	public function setNomformenu($nomformenu = null) {
		// $this->nomformenu = $nomformenu;
		$this->initNomformenu();
	
		return $this;
	}

	/**
	 * Get nomformenu
	 *
	 * @return string 
	 */
	public function getNomformenu() {
		return $this->nomformenu;
	}

	/**
	 * Set telmobile
	 *
	 * @param string $telmobile
	 * @return magasin
	 */
	public function setTelmobile($telmobile) {
		$this->telmobile = $telmobile;
	
		return $this;
	}

	/**
	 * Get telmobile
	 *
	 * @return string 
	 */
	public function getTelmobile() {
		return $this->telmobile;
	}

	/**
	 * Set adresse
	 *
	 * @param string $adresse
	 * @return magasin
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
	 * Set responsable
	 *
	 * @param string $responsable
	 * @return magasin
	 */
	public function setResponsable($responsable) {
		$this->responsable = $responsable;
	
		return $this;
	}

	/**
	 * Get responsable
	 *
	 * @return string 
	 */
	public function getResponsable() {
		return $this->responsable;
	}

	/**
	 * Set siteurl
	 *
	 * @param string $siteurl
	 * @return magasin
	 */
	public function setSiteurl($siteurl) {
		$this->siteurl = $siteurl;
	
		return $this;
	}

	/**
	 * Get siteurl
	 *
	 * @return string 
	 */
	public function getSiteurl() {
		return $this->siteurl;
	}

	/**
	 * Set cp
	 *
	 * @param string $cp
	 * @return magasin
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
	 * Set secteur
	 *
	 * @param string $secteur
	 * @return magasin
	 */
	public function setSecteur($secteur) {
		$this->secteur = $secteur;
	
		return $this;
	}

	/**
	 * Get secteur
	 *
	 * @return string 
	 */
	public function getSecteur() {
		return $this->secteur;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 * @return magasin
	 */
	public function setEmail($email) {
		if (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
			$this->email = $email;
		} else $this->email = null;
	
		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string 
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set commentaire
	 *
	 * @param string $commentaire
	 * @return magasin
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
	 * Set departement
	 *
	 * @param string $departement
	 * @return magasin
	 */
	public function setDepartement($departement) {
		$this->departement = $departement;
		$this->initNomformenu();
	
		return $this;
	}

	/**
	 * Get departement
	 *
	 * @return string 
	 */
	public function getDepartement() {
		return $this->departement;
	}

	/**
	 * Set ville
	 *
	 * @param string $ville
	 * @return magasin
	 */
	public function setVille($ville) {
		$this->ville = $ville;
		$this->initNomformenu();
	
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
	 * Set telephone
	 *
	 * @param string $telephone
	 * @return magasin
	 */
	public function setTelephone($telephone) {
		$this->telephone = $telephone;
	
		return $this;
	}

	/**
	 * Get telephone
	 *
	 * @return string 
	 */
	public function getTelephone() {
		return $this->telephone;
	}

	/**
	 * Set type
	 *
	 * @param string $type
	 * @return magasin
	 */
	public function setType($type) {
		$this->type = $type;
	
		return $this;
	}

	/**
	 * Get type
	 *
	 * @return string 
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Set magvoisin1
	 *
	 * @param string $magvoisin1
	 * @return magasin
	 */
	public function setMagvoisin1($magvoisin1) {
		$this->magvoisin1 = $magvoisin1;
	
		return $this;
	}

	/**
	 * Get magvoisin1
	 *
	 * @return string 
	 */
	public function getMagvoisin1() {
		return $this->magvoisin1;
	}

	/**
	 * Set magvoisin2
	 *
	 * @param string $magvoisin2
	 * @return magasin
	 */
	public function setMagvoisin2($magvoisin2) {
		$this->magvoisin2 = $magvoisin2;
	
		return $this;
	}

	/**
	 * Get magvoisin2
	 *
	 * @return string 
	 */
	public function getMagvoisin2() {
		return $this->magvoisin2;
	}

	/**
	 * Set raisonsociale
	 *
	 * @param string $raisonsociale
	 * @return magasin
	 */
	public function setRaisonsociale($raisonsociale = null) {
		if(($raisonsociale === null) || ($raisonsociale === "")) $raisonsociale = $this->getNommagasin();
		$this->raisonsociale = $raisonsociale;
	
		return $this;
	}

	/**
	 * Get raisonsociale
	 *
	 * @return string 
	 */
	public function getRaisonsociale() {
		return $this->raisonsociale;
	}

	/**
	 * Set titreSeo
	 *
	 * @param string $titreSeo
	 * @return magasin
	 */
	public function setTitreSeo($titreSeo) {
		$this->titreSeo = $titreSeo;
	
		return $this;
	}

	/**
	 * Get titreSeo
	 *
	 * @return string 
	 */
	public function getTitreSeo() {
		return $this->titreSeo;
	}

	/**
	 * Set descSeo
	 *
	 * @param string $descSeo
	 * @return magasin
	 */
	public function setDescSeo($descSeo) {
		$this->descSeo = $descSeo;
	
		return $this;
	}

	/**
	 * Get descSeo
	 *
	 * @return string 
	 */
	public function getDescSeo() {
		return $this->descSeo;
	}

	/**
	 * Set metakey
	 *
	 * @param string $metakey
	 * @return magasin
	 */
	public function setMetakey($metakey) {
		$this->metakey = $metakey;
	
		return $this;
	}

	/**
	 * Get metakey
	 *
	 * @return string 
	 */
	public function getMetakey() {
		return $this->metakey;
	}

	/**
	 * Set typemagasin
	 *
	 * @param string $typemagasin
	 * @return magasin
	 */
	public function setTypemagasin($typemagasin = null) {
		if($typemagasin === null) {
			reset($this->listTypesMagasins);
			$typemagasin = key($this->listTypesMagasins);
		}
		$this->typemagasin = $typemagasin;
	
		return $this;
	}

	/**
	 * Get typemagasin
	 *
	 * @return string 
	 */
	public function getTypemagasin() {
		return $this->typemagasin;
	}

	/**
	 * Get typemagasin
	 *
	 * @return string 
	 */
	public function getNomTypemagasin() {
		return isset($this->listTypesMagasins[$this->typemagasin]) ? $this->listTypesMagasins[$this->typemagasin] : null;
	}

	/**
	 * Set nommagasin
	 *
	 * @param string $nommagasin
	 * @return magasin
	 */
	public function setNommagasin($nommagasin = null) {
		if(($this->getRaisonsociale() === null) || ($this->getRaisonsociale() === "")) $this->setRaisonsociale($nommagasin);
		$this->nommagasin = $nommagasin;
		if($nommagasin === "" || $nommagasin === null) {
			$this->nommagasin = $this->listTypesMagasins[$this->getTypemagasin()];
		}
		$this->initNomformenu();
	
		return $this;
	}

	/**
	 * Get nommagasin
	 *
	 * @return string 
	 */
	public function getNommagasin() {
		return $this->nommagasin;
	}

	/**
	 * Set posithoraire
	 *
	 * @param integer $posithoraire
	 * @return magasin
	 */
	public function setPosithoraire($posithoraire) {
		$this->posithoraire = $posithoraire;
	
		return $this;
	}

	/**
	 * Get posithoraire
	 *
	 * @return integer 
	 */
	public function getPosithoraire() {
		return $this->posithoraire;
	}

	/**
	 * Set item
	 *
	 * @param string $item
	 * @return magasin
	 */
	public function setItem($item) {
		$this->item = $item;
	
		return $this;
	}

	/**
	 * Get item
	 *
	 * @return string 
	 */
	public function getItem() {
		return $this->item;
	}

	/**
	 * Set statut
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\statut $statut
	 * @return magasin
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


}
