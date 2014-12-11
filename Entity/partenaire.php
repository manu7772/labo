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
 * @UniqueEntity(fields={"nom"}, message="Ce partenaire existe déjà.")
 */
abstract class partenaire {

    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=false)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="accroche", type="string", length=255, nullable=true)
     */
    protected $accroche;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     * @Assert\Url(message = "Vous devez indiquer une URL valide et complète.")
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="urlflux", type="string", length=255, nullable=true)
     * @Assert\Url(message = "Vous devez indiquer une URL valide et complète.")
     */
    protected $urlflux;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Assert\Email(message = "Vous devez indiquer un mail valide et complet.")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20, nullable=true)
     */
    protected $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=20, nullable=true)
     */
    protected $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     */
    protected $descriptif;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    protected $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="codepub", type="text", nullable=true)
     */
    protected $codepub;

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
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    protected $image;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\adresse", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    protected $adresse;

    /**
     * @var integer
     *
     * @ORM\Column(name="niveau", type="integer", nullable=false)
     */
    protected $niveau;

    /**
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\version")
     */
    protected $versions;

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



    public function __construct() {
        $this->dateCreation = new \Datetime();
        $this->dateMaj = null;
        $this->versions = new ArrayCollection();
        $this->niveau = 2; // 1 à 3
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return partenaire
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set accroche
     *
     * @param string $accroche
     * @return partenaire
     */
    public function setAccroche($accroche = null)
    {
        $this->accroche = $accroche;
    
        return $this;
    }

    /**
     * Get accroche
     *
     * @return string 
     */
    public function getAccroche()
    {
        return $this->accroche;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return partenaire
     */
    public function setUrl($url = null)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set urlflux
     *
     * @param string $urlflux
     * @return partenaire
     */
    public function setUrlflux($urlflux = null)
    {
        $this->urlflux = $urlflux;
    
        return $this;
    }

    /**
     * Get urlflux
     *
     * @return string 
     */
    public function getUrlflux()
    {
        return $this->urlflux;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return partenaire
     */
    public function setEmail($email = null)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return partenaire
     */
    public function setTel($tel = null)
    {
        $this->tel = $tel;
    
        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return partenaire
     */
    public function setFax($fax = null)
    {
        $this->fax = $fax;
    
        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set descriptif
     *
     * @param string $descriptif
     * @return partenaire
     */
    public function setDescriptif($descriptif = null)
    {
        $this->descriptif = $descriptif;
    
        return $this;
    }

    /**
     * Get descriptif
     *
     * @return string 
     */
    public function getDescriptif()
    {
        return $this->descriptif;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return partenaire
     */
    public function setCommentaire($commentaire = null)
    {
        $this->commentaire = $commentaire;
    
        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set codepub
     *
     * @param string $codepub
     * @return partenaire
     */
    public function setCodepub($codepub = null)
    {
        $this->codepub = $codepub;
    
        return $this;
    }

    /**
     * Get codepub
     *
     * @return string 
     */
    public function getCodepub()
    {
        return $this->codepub;
    }

    /**
     * Set logo
     *
     * @param \AcmeGroup\LaboBundle\Entity\image $logo
     * @return partenaire
     */
    public function setLogo(\AcmeGroup\LaboBundle\Entity\image $logo = null)
    {
        $this->logo = $logo;
    
        return $this;
    }

    /**
     * Get logo
     *
     * @return \AcmeGroup\LaboBundle\Entity\image 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set image
     *
     * @param \AcmeGroup\LaboBundle\Entity\image $image
     * @return partenaire
     */
    public function setImage(\AcmeGroup\LaboBundle\Entity\image $image = null)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return \AcmeGroup\LaboBundle\Entity\image 
     */
    public function getImage()
    {
        return $this->image;
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

    /**
     * Set niveau
     *
     * @param integer $niveau
     * @return partenaire
     */
    public function setNiveau($niveau)
    {
        if($niveau > 3) $niveau = 3;
        if($niveau < 1) $niveau = 1;
        $this->niveau = $niveau;
    
        return $this;
    }

    /**
     * Get niveau
     *
     * @return integer 
     */
    public function getNiveau()
    {
        return $this->niveau;
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
     * @param \AcmeGroup\LaboBundle\Entity\version $versions
     * @return partenaire
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return partenaire
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
     * @return partenaire
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
     * Set statut
     *
     * @param integer $statut
     * @return baseEntity
     */
    public function setStatut(\AcmeGroup\LaboBundle\Entity\statut $statut) {
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


}
