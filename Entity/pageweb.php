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
abstract class pageweb {

    protected $id;

    /**
     * @var string
     * @ORM\Column(name="nom", type="string", length=100, nullable=false)
     */
    protected $nom;

    /**
     * @var string
     * @ORM\Column(name="code", type="text", nullable=true, unique=false)
     */
    protected $code;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=100, nullable=true, unique=false)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(name="titreh1", type="string", length=255, nullable=true, unique=false)
     */
    protected $titreh1;

    /**
     * @var string
     * @ORM\Column(name="metatitle", type="text", nullable=true, unique=false)
     */
    protected $metatitle;

    /**
     * @var string
     * @ORM\Column(name="metadescription", type="text", nullable=true, unique=false)
     */
    protected $metadescription;

    /**
     * @var string
     * @ORM\Column(name="fichierhtml", type="string", length=255, nullable=true, unique=false)
     */
    protected $fichierhtml;

    /**
     * @var string
     * @ORM\Column(name="route", type="string", length=255)
     */
    protected $route;

    /**
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @var array
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\collection")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    protected $diaporama;

    /**
     * @var array
     *
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    protected $firstmedia;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\image")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    protected $medias;

    /**
     * @var array
     * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\version")
     */
    protected $versions;

    /**
     * @var \DateTime
     * @ORM\Column(name="dateCreation", type="datetime", nullable=false)
     */
    protected $dateCreation;

    /**
     * @var \DateTime
     * @ORM\Column(name="dateMaj", type="datetime", nullable=true)
     */
    protected $dateMaj;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\tag", inversedBy="pagewebs")
     */
    protected $tags;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\richtext")
     */
    protected $richtexts;


    public function __construct() {
        $this->dateCreation = new \Datetime();
        $this->dateMaj = null;
        $this->versions = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->richtexts = new ArrayCollection();
        $this->medias = new ArrayCollection();
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
     * @return pageweb
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
     * Set code
     *
     * @param string $code
     * @return pageweb
     */
    public function setCode($code = null)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return pageweb
     */
    public function setTitle($title = null)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set titreh1
     *
     * @param string $titreh1
     * @return pageweb
     */
    public function setTitreh1($titreh1 = null)
    {
        $this->titreh1 = $titreh1;
    
        return $this;
    }

    /**
     * Get titreh1
     *
     * @return string 
     */
    public function getTitreh1()
    {
        return $this->titreh1;
    }

    /**
     * Set metatitle
     *
     * @param string $metatitle
     * @return pageweb
     */
    public function setMetatitle($metatitle = null)
    {
        $this->metatitle = $metatitle;
    
        return $this;
    }

    /**
     * Get metatitle
     *
     * @return string 
     */
    public function getMetatitle()
    {
        return $this->metatitle;
    }

    /**
     * Set metadescription
     *
     * @param string $metadescription
     * @return pageweb
     */
    public function setMetadescription($metadescription = null)
    {
        $this->metadescription = $metadescription;
    
        return $this;
    }

    /**
     * Get metadescription
     *
     * @return string 
     */
    public function getMetadescription()
    {
        return $this->metadescription;
    }

    /**
     * Set fichierhtml
     *
     * @param string $fichierhtml
     * @return pageweb
     */
    public function setFichierhtml($fichierhtml = null)
    {
        $this->fichierhtml = $fichierhtml;
    
        return $this;
    }

    /**
     * Get fichierhtml
     *
     * @return string 
     */
    public function getFichierhtml()
    {
        return $this->fichierhtml;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return pageweb
     */
    public function setRoute($route)
    {
        $this->route = $route;
    
        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
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
     * Set diaporama
     *
     * @param \AcmeGroup\LaboBundle\Entity\collection $diaporama
     * @return baseEntity
     */
    public function setDiaporama(\AcmeGroup\LaboBundle\Entity\collection $diaporama = null) {
        $this->diaporama = $diaporama;
        return $this;
    }

    /**
     * Get diaporama
     *
     * @return \AcmeGroup\LaboBundle\Entity\collection
     */
    public function getDiaporama() {
        return $this->diaporama;
    }

    /**
     * Add versions
     *
     * @param \AcmeGroup\LaboBundle\Entity\version $versions
     * @return pageweb
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
     * @return pageweb
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
     * @return pageweb
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
     * Add tag
     *
     * @param \AcmeGroup\LaboBundle\Entity\tag $tag
     * @return pageweb
     */
    public function addTag(\AcmeGroup\LaboBundle\Entity\tag $tag) {
        $this->tags->add($tag);
        $tag->addPageweb($this);
    
        return $this;
    }

    /**
     * Remove tag
     *
     * @param \AcmeGroup\LaboBundle\Entity\tag $tag
     */
    public function removeTag(\AcmeGroup\LaboBundle\Entity\tag $tag) {
        $this->tags->removeElement($tag);
        $tag->removePageweb($this);
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
     * Add richtext
     *
     * @param \AcmeGroup\LaboBundle\Entity\richtext $richtext
     * @return pageweb
     */
    public function addRichtext(\AcmeGroup\LaboBundle\Entity\richtext $richtext) {
        $this->richtexts->add($richtext);
    
        return $this;
    }

    /**
     * Remove richtext
     *
     * @param \AcmeGroup\LaboBundle\Entity\tag $tag
     */
    public function removeRichtext(\AcmeGroup\LaboBundle\Entity\richtext $richtext) {
        $this->richtexts->removeElement($richtext);
    }

    /**
     * Get richtexts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRichtexts() {
        return $this->richtexts;
    }

    /**
     * Set firstmedia
     *
     * @param string $firstmedia
     * @return pageweb
     */
    public function setFirstmedia($firstmedia)
    {
        $this->firstmedia = $firstmedia;
    
        return $this;
    }

    /**
     * Get firstmedia
     *
     * @return string 
     */
    public function getFirstmedia()
    {
        return $this->firstmedia;
    }

    /**
     * Add media
     *
     * @param \AcmeGroup\LaboBundle\Entity\image $image
     * @return pageweb
     */
    public function addMedia(\AcmeGroup\LaboBundle\Entity\image $image) {
        $this->medias[] = $image;
    
        return $this;
    }

    /**
     * Remove media
     *
     * @param \AcmeGroup\LaboBundle\Entity\image $image
     */
    public function removeMedia(\AcmeGroup\LaboBundle\Entity\image $image) {
        $this->medias->removeElement($image);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedias() {
        return $this->medias;
    }


}
