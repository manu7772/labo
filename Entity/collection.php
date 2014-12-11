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
abstract class collection {
    
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptif", type="text", nullable=true)
     */
    protected $descriptif;

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
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
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
     * @var integer
     *
     * @ORM\Column(name="vitesse", type="integer", nullable=false)
     */
    protected $vitesse;


    protected $parametres;


    public function __construct() {
        $this->dateCreation = new \Datetime();
        $this->dateMaj = null;
        $this->dateExpiration = null;

        $this->firstmedia = null;
        $this->medias = new ArrayCollection();
        $this->versions = new ArrayCollection();
        $this->parametres = array();
        $this->vitesse = 5000;
    }

    public function __set($name, $arguments) {
        $this->parametres[$name] = $arguments;
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
     * @return collection
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
     * Set descriptif
     *
     * @param string $descriptif
     * @return collection
     */
    public function setDescriptif($descriptif)
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
     * Set firstmedia
     *
     * @param string $firstmedia
     * @return collection
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
     * @return collection
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

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return collection
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
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
     * @return collection
     */
    public function setDateMaj($dateMaj)
    {
        $this->dateMaj = $dateMaj;
    
        return $this;
    }

    /**
     * Get dateMaj
     *
     * @return \DateTime 
     */
    public function getDateMaj()
    {
        return $this->dateMaj;
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
     * Set vitesse
     *
     * @param integer $vitesse
     * @return collection
     */
    public function setVitesse($vitesse)
    {
        $this->vitesse = $vitesse;
    
        return $this;
    }

    /**
     * Get vitesse
     *
     * @return integer 
     */
    public function getVitesse()
    {
        return $this->vitesse;
    }



}
