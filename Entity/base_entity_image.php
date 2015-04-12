<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use \Datetime;
use \Imagick;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;
// Base
use labo\Bundle\TestmanuBundle\Entity\base_entity;
// Entities
use labo\Bundle\TestmanuBundle\Entity\typeImage;

/**
 * @ORM\MappedSuperclass
 */
abstract class base_entity_image extends base_entity {

	/**
	 * @var string
	 *
	 * @ORM\Column(name="url", type="text", nullable=true, unique=false)
	 */
	protected $url;

	/**
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\typeImage")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $typeImages;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fichierOrigine", type="string", length=200)
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $fichierOrigine;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fichierNom", type="string", length=200)
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $fichierNom;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="tailleX", type="integer", nullable=true, unique=false)
	 */
	protected $tailleX;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="tailleY", type="integer", nullable=true, unique=false)
	 */
	protected $tailleY;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="tailleMo", type="integer", nullable=true, unique=false)
	 */
	protected $tailleMo;

	/**
	 * @Assert\File(maxSize="6000000")
	 */
	protected $file;

	protected $tempFileName;
	protected $alt;
	protected $ext;
	// Eléments de formulaire
	protected $remove;

	public function __construct() {
		parent::__construct();
		$this->alt = "image";
		$this->fichierNom = "";
		$this->typeImages = new ArrayCollection();
		$this->tempFileName = null;
		$this->fichierOrigine = null;
		$this->ext = null;
		$this->remove = false; // pour effacer l'image
	}

	public function __call($name, $arguments = null) {
		switch ($name) {
			case 'is'.ucfirst($this->getName()):
				$reponse = true;
				break;
			default:
				$reponse = false;
				break;
		}
		return $reponse;
	}

	public function getParentName() {
		return parent::getName();
	}

	public function getName() {
		return 'base_entity_image';
	}

	/**
	 * @ORM/PreUpdate
	 * @ORM/PrePersist
	 */
	public function verifBase_entity_image() {
		$verifMethod = 'verif'.ucfirst($this->getParentName());
		if(method_exists($this, $verifMethod)) {
			$this->$verifMethod();
		}
		$this->defineNomCourt();
	}

	/**
	 * @Assert/True(message = "Cette entité n'est pas valide.")
	 */
	public function isBase_entity_imageValid() {
		$valid = true;
		$validMethod = 'is'.ucfirst($this->getParentName()).'Valid';
		if(method_exists($this, $validMethod)) {
			$valid = $this->$validMethod();
		}
		// autres vérifications, si le parent est valide…
		if($valid === true) {
			//
		}

		return $valid;
	}


	/**
	 * Set remove
	 *
	 * @param boolean $remove
	 * @return baseEntity
	 */
	public function setRemove($remove = true) {
		if(!is_bool($remove)) $remove = false;
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
	 * Add typeImages
	 *
	 * @param typeImage $typeImages
	 * @return images
	 */
	public function addTypeImage(typeImage $typeImage) {
		$this->typeImages[] = $typeImage;
		$typeImage->addImage($this);
	
		return $this;
	}

	/**
	 * Remove typeImages
	 *
	 * @param typeImage $typeImage
	 */
	public function removeTypeImage(typeImage $typeImage) {
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

}