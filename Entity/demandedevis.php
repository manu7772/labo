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
abstract class demandedevis {
    
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="civilite", type="string", length=30)
     */
    protected $civilite;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=100)
     */
    protected $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="entreprise", type="string", length=100)
     */
    protected $entreprise;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20)
     */
    protected $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="port", type="string", length=20, nullable=true, unique=false)
     */
    protected $port;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=20, nullable=true, unique=false)
     */
    protected $fax;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\adresse", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    protected $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="demande", type="text")
     */
    protected $demande;

    /**
     * @var boolean
     *
     * @ORM\Column(name="copie", type="boolean")
     */
    protected $copie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    protected $dateCreation;

    /**
     * @var array
     *
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\version")
     * @ORM\JoinColumn(nullable=false, unique=false)
     */
    protected $version;


    public function __construct() {
        $this->dateCreation = new \Datetime();
        $this->copie = true;
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
     * Set civilite
     *
     * @param string $civilite
     * @return demandedevis
     */
    public function setCivilite($civilite) {
        $this->civilite = $civilite;
    
        return $this;
    }

    /**
     * Get civilite
     *
     * @return string 
     */
    public function getCivilite() {
        return $this->civilite;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return demandedevis
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
     * Set prenom
     *
     * @param string $prenom
     * @return demandedevis
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * Set entreprise
     *
     * @param string $entreprise
     * @return demandedevis
     */
    public function setEntreprise($entreprise) {
        $this->entreprise = $entreprise;
    
        return $this;
    }

    /**
     * Get entreprise
     *
     * @return string 
     */
    public function getEntreprise() {
        return $this->entreprise;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return demandedevis
     */
    public function setEmail($email) {
        $this->email = $email;
    
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
     * Set tel
     *
     * @param string $tel
     * @return demandedevis
     */
    public function setTel($tel) {
        $this->tel = $tel;
    
        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel() {
        return $this->tel;
    }

    /**
     * Set port
     *
     * @param string $port
     * @return demandedevis
     */
    public function setPort($port) {
        $this->port = $port;
    
        return $this;
    }

    /**
     * Get port
     *
     * @return string 
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return demandedevis
     */
    public function setFax($fax) {
        $this->fax = $fax;
    
        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax() {
        return $this->fax;
    }

    /**
     * Set adresse
     *
     * @param \AcmeGroup\LaboBundle\Entity\adresse $adresse
     * @return demandedevis
     */
    public function setAdresse(\AcmeGroup\LaboBundle\Entity\adresse $adresse = null) {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return \AcmeGroup\LaboBundle\Entity\adresse 
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * Set demande
     *
     * @param string $demande
     * @return demandedevis
     */
    public function setDemande($demande) {
        $this->demande = $demande;
    
        return $this;
    }

    /**
     * Get demande
     *
     * @return string 
     */
    public function getDemande() {
        return $this->demande;
    }

    /**
     * Set copie
     *
     * @param boolean $copie
     * @return demandedevis
     */
    public function setCopie($copie) {
        $this->copie = $copie;
    
        return $this;
    }

    /**
     * Get copie
     *
     * @return boolean 
     */
    public function getCopie() {
        return $this->copie;
    }

    /**
     * Set version
     *
     * @param \AcmeGroup\LaboBundle\Entity\version $version
     * @return demandedevis
     */
    public function setVersion(\AcmeGroup\LaboBundle\Entity\version $version) {
        $this->version = $version;
    
        return $this;
    }

    /**
     * Get version
     *
     * @return \AcmeGroup\LaboBundle\Entity\version 
     */
    public function getVersion() {
        return $this->version;
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

}
