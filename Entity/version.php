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
 * @UniqueEntity(fields={"siren"}, message="Cette entreprise est déjà enregistrée")
 */
abstract class version {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=30, nullable=false, unique=true)
	 * @Assert\NotBlank(message = "Vous devez remplir ce champ.")
	 * @Assert\Length(
	 *      min = "3",
	 *      max = "30",
	 *      minMessage = "Le nom doit comporter au minimum {{ limit }} lettres.",
	 *      maxMessage = "Le nom doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	protected $nom;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="defaut", type="boolean")
	 */
	protected $defaut;

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
	 * @var string
	 *
	 * @ORM\Column(name="accroche", type="string", length=200, nullable=true, unique=false)
	 * @Assert\Length(
	 *      min = "1",
	 *      max = "200",
	 *      minMessage = "L'accroche doit comporter au moins {{ limit }} lettres.",
	 *      maxMessage = "L'accroche doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	protected $accroche;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="tvaIntra", type="string", length=100, nullable=true, unique=false)
	 */
	protected $tvaIntra;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="siren", type="string", length=100, nullable=true, unique=false)
	 */
	protected $siren;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="telpublic", type="string", length=25, nullable=true, unique=false)
	 * @Assert\Length(
	 *      min = "10",
	 *      max = "14",
	 *      minMessage = "Le téléphone doit comporter au moins {{ limit }} chiffres.",
	 *      maxMessage = "Le téléphone doit comporter au plus {{ limit }} chiffres."
	 * )
	 *
	 */
	protected $telpublic;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true, unique=false)
	 */
	protected $descriptif;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nomDomaine", type="string", length=200, nullable=false, unique=true)
	 * @Assert\Url(message = "Vous devez indiquer une URL valide et complète.")
	 * 
	 */
	protected $nomDomaine;

	/**
	 * @var integer
	 *
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $logo;

	/**
	 * @var integer
	 *
	 * @ORM\OneToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=true)
	 */
	protected $favicon;

	/**
	 * @var integer
	 *
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $imageEntete;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="couleurFond", type="string", length=30, nullable=false, unique=false)
	 */
	protected $couleurFond;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fichierCSS", type="string", length=30, nullable=true, unique=false)
	 */
	protected $fichierCSS;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="templateIndex", type="string", length=30, nullable=false, unique=false)
	 */
	protected $templateIndex;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	protected $slug;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\adresse", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    protected $adresse;


	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->dateMaj = null;
		$this->couleurFond = "#FFFFFF";
		$this->fichierCSS = null;
		$this->defaut = false;
		$this->templateIndex = "Site";
	}


	/**
	 * @Assert\True(message = "Vous devez renseigner soit le numéro TVAintra, soit le SIREN.")
	 */
	public function isVersionValid() {
		if($this->tvaIntra || $this->siren) return true;
		else return false;
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
	 * Set nom
	 *
	 * @param string $nom
	 * @return version
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
	 * Set defaut
	 *
	 * @param boolean $defaut
	 * @return version
	 */
	public function setDefaut($defaut) {
		$this->defaut = $defaut;
	
		return $this;
	}

	/**
	 * Get defaut
	 *
	 * @return boolean 
	 */
	public function getDefaut() {
		return $this->defaut;
	}

	/**
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return version
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
	 * Set accroche
	 *
	 * @param string $accroche
	 * @return version
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
	 * Set tvaIntra
	 *
	 * @param string $tvaIntra
	 * @return version
	 */
	public function setTvaIntra($tvaIntra) {
		$this->tvaIntra = $tvaIntra;
	
		return $this;
	}

	/**
	 * Get tvaIntra
	 *
	 * @return string 
	 */
	public function getTvaIntra() {
		return $this->tvaIntra;
	}

	/**
	 * Set siren
	 *
	 * @param string $siren
	 * @return version
	 */
	public function setSiren($siren) {
		$this->siren = $siren;
	
		return $this;
	}

	/**
	 * Get siren
	 *
	 * @return string 
	 */
	public function getSiren() {
		return $this->siren;
	}

	/**
	 * Set telpublic
	 *
	 * @param string $telpublic
	 * @return version
	 */
	public function setTelpublic($telpublic) {
		$this->telpublic = $telpublic;
	
		return $this;
	}

	/**
	 * Get telpublic
	 *
	 * @return string 
	 */
	public function getTelpublic() {
		return $this->telpublic;
	}

	/**
	 * Set descriptif
	 *
	 * @param string $descriptif
	 * @return version
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
	 * Set nomDomaine
	 *
	 * @param string $nomDomaine
	 * @return version
	 */
	public function setNomDomaine($nomDomaine) {
		$this->nomDomaine = $nomDomaine;
	
		return $this;
	}

	/**
	 * Get nomDomaine
	 *
	 * @return string 
	 */
	public function getNomDomaine() {
		return $this->nomDomaine;
	}

	/**
	 * Set couleurFond
	 *
	 * @param string $couleurFond
	 * @return version
	 */
	public function setCouleurFond($couleurFond) {
		$this->couleurFond = $couleurFond;
	
		return $this;
	}

	/**
	 * Get couleurFond
	 *
	 * @return string 
	 */
	public function getCouleurFond() {
		return $this->couleurFond;
	}

	/**
	 * Set fichierCSS
	 *
	 * @param string $fichierCSS
	 * @return version
	 */
	public function setFichierCSS($fichierCSS) {
		$this->fichierCSS = $fichierCSS;
	
		return $this;
	}

	/**
	 * Get fichierCSS
	 *
	 * @return string 
	 */
	public function getFichierCSS() {
		return $this->fichierCSS;
	}

	/**
	 * Set templateIndex
	 *
	 * @param string $templateIndex
	 * @return version
	 */
	public function setTemplateIndex($templateIndex) {
		$this->templateIndex = $templateIndex;
	
		return $this;
	}

	/**
	 * Get templateIndex
	 *
	 * @return string 
	 */
	public function getTemplateIndex() {
		return $this->templateIndex;
	}

	/**
	 * Set logo
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $logo
	 * @return version
	 */
	public function setLogo(\AcmeGroup\LaboBundle\Entity\image $logo) {
		$this->logo = $logo;
	
		return $this;
	}

	/**
	 * Get logo
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\image 
	 */
	public function getLogo() {
		return $this->logo;
	}

	/**
	 * Set favicon
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $favicon
	 * @return version
	 */
	public function setFavicon(\AcmeGroup\LaboBundle\Entity\image $favicon) {
		$this->favicon = $favicon;
	
		return $this;
	}

	/**
	 * Get favicon
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\image 
	 */
	public function getFavicon() {
		return $this->favicon;
	}

	/**
	 * Set imageEntete
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $imageEntete
	 * @return version
	 */
	public function setImageEntete(\AcmeGroup\LaboBundle\Entity\image $imageEntete = null) {
		$this->imageEntete = $imageEntete;
	
		return $this;
	}

	/**
	 * Get imageEntete
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\image 
	 */
	public function getImageEntete() {
		return $this->imageEntete;
	}

    /**
     * Set adresse
     *
     * @param \AcmeGroup\LaboBundle\Entity\adresse $adresse
     * @return partenaire
     */
    public function setAdresse(\AcmeGroup\LaboBundle\Entity\adresse $adresse = null)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return \AcmeGroup\LaboBundle\Entity\adresse 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

}