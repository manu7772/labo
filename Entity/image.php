<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Tree
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\imageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class image {

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
	 * @ORM\Column(name="nom", type="string", length=100, nullable=true, unique=false)
	 */
	private $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true, unique=false)
	 */
	private $descriptif;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="url", type="text", nullable=true, unique=false)
	 */
	private $url;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="notation", type="smallint", nullable=true, unique=false)
	 */
	private $notation;

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
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\typeImage")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $typeImages;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fichierOrigine", type="string", length=200)
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $fichierOrigine;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fichierNom", type="string", length=200)
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $fichierNom;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="tailleX", type="integer", nullable=true, unique=false)
	 */
	private $tailleX;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="tailleY", type="integer", nullable=true, unique=false)
	 */
	private $tailleY;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="tailleMo", type="integer", nullable=true, unique=false)
	 */
	private $tailleMo;

	/**
	 * @Assert\File(maxSize="6000000")
	 */
	private $file;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	private $slug;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="images")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $propUser;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\version")
	 */
	private $versions;

	private $tempFileName;
	private $ext;
	// Eléments de formulaire
	private $remove;

	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->dateMaj = null;
		$this->alt = "image";
		$this->fichierNom = "";
		$this->typeImages = new ArrayCollection();
		$this->versions = new ArrayCollection();
		$this->tempFileName = null;
		$this->fichierOrigine = null;
		$this->ext = null;
		$this->remove = false; // pour effacer l'image
	}

	/**
	 * Set remove
	 *
	 * @param boolean $remove
	 * @return baseEntity
	 */
	public function setRemove($remove) {
		$this->remove = $remove;
	
		return $this;
	}

	/**
	 * Get remove
	 *
	 * @return boolean 
	 */
	public function getRemove() {
		return $this->remove;
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
	 * @return baseEntity
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
	 * Set url
	 *
	 * @param string $url
	 * @return baseEntity
	 */
	public function setUrl($url = null) {
		$this->url = $url;
	
		return $this;
	}

	/**
	 * Get url
	 *
	 * @return string 
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Set notation
	 *
	 * @param integer $notation
	 * @return baseEntity
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
	 * Add typeImages
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\typeImage $typeImages
	 * @return images
	 */
	public function addTypeImage(\labo\Bundle\TestmanuBundle\Entity\typeImage $typeImage) {
		$this->typeImages[] = $typeImage;
	
		return $this;
	}

	/**
	 * Remove typeImages
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\typeImage $typeImage
	 */
	public function removeTypeImage(\labo\Bundle\TestmanuBundle\Entity\typeImage $typeImage) {
		$this->typeImages->removeElement($typeImage);
	}

	/**
	 * Get typeImage
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getTypeImages() {
		return $this->typeImages;
	}

	/**
	 * Set fichierOrigine
	 *
	 * @param string $fichierOrigine
	 * @return image
	 */
	public function setFichierOrigine($fichierOrigine = null) {
		$this->fichierOrigine = $fichierOrigine;
	
		return $this;
	}

	/**
	 * Get fichierOrigine
	 *
	 * @return string 
	 */
	public function getFichierOrigine() {
		return $this->fichierOrigine;
	}

	/**
	 * Set fichierNom
	 *
	 * @param string $fichierNom
	 * @return image
	 */
	public function setFichierNom($fichierNom = null) {
		$this->fichierNom = $fichierNom;
	
		return $this;
	}

	/**
	 * Get fichierNom
	 *
	 * @return string 
	 */
	public function getFichierNom() {
		return $this->fichierNom;
	}

	/**
	 * Set tailleX
	 *
	 * @param integer $tailleX
	 * @return image
	 */
	public function setTailleX($tailleX) {
		$this->tailleX = $tailleX;
	
		return $this;
	}

	/**
	 * Get tailleX
	 *
	 * @return integer 
	 */
	public function getTailleX() {
		return $this->tailleX;
	}

	/**
	 * Set tailleY
	 *
	 * @param integer $tailleY
	 * @return image
	 */
	public function setTailleY($tailleY) {
		$this->tailleY = $tailleY;
	
		return $this;
	}

	/**
	 * Get tailleY
	 *
	 * @return integer 
	 */
	public function getTailleY() {
		return $this->tailleY;
	}

	/**
	 * Set tailleMo
	 *
	 * @param integer $tailleMo
	 * @return image
	 */
	public function setTailleMo($tailleMo) {
		$this->tailleMo = $tailleMo;
	
		return $this;
	}

	/**
	 * Get tailleMo
	 *
	 * @return integer 
	 */
	public function getTailleMo() {
		return $this->tailleMo;
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
	 * Set file
	 *
	 * @param integer $file
	 * @return image
	 */
	public function setFile(UploadedFile $file = null) {
		$this->file = $file;
		if(null !== $this->fichierOrigine) {
			$this->tempFileName = $this->fichierOrigine;
			$this->fichierOrigine = null;
		}
	
		return $this;
	}

	/**
	 * Get file
	 *
	 * @return UploadedFile 
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * Set tempFileName
	 *
	 */
	public function setTempFileName($tempFileName = null) {
		$this->tempFileName = $tempFileName;
		return $this;
	}

	/**
	 * Get tempFileName
	 *
	 * @return string 
	 */
	public function getTempFileName() {
		return $this->tempFileName;
	}

	/**
	 * Set ext
	 *
	 */
	public function setExt($ext) {
		$this->ext = $ext;
		return $this;
	}

	/**
	 * Get ext
	 *
	 * @return string 
	 */
	public function getExt() {
		return $this->ext;
	}

	/**
	 * Set propUser
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $propUser
	 * @return image
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
	 * Add versions
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\version $versions
	 * @return image
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


}