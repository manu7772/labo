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
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class fichierPdf {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=255, nullable=true, unique=true)
	 */
	protected $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true, unique=false)
	 */
	protected $descriptif;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateCreation", type="datetime")
	 */
	protected $dateCreation;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fichierOrigine", type="string", length=255)
	 */
	protected $fichierOrigine;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fichierNom", type="string", length=255)
	 */
	protected $fichierNom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="thumbFichierNom", type="string", length=255, nullable=true, unique=true)
	 */
	protected $thumbFichierNom;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="tailleMo", type="integer", nullable=true, unique=false)
	 */
	protected $tailleMo;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nbpages", type="integer", nullable=true, unique=false)
	 */
	protected $nbpages;

	/**
	 * @Assert\File(maxSize="6000000")
	 */
	protected $file;

	/**
	 * @Gedmo\Slug(fields={"fichierOrigine"})
	 * @ORM\Column(length=128, unique=true)
	 */
	protected $slug;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="fichierPdfs")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $propUser;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\version")
	 */
	protected $versions;

	protected $tempFilename;

	protected $fixturesDeactivate;

	protected $fichierThumbExt;

	protected $aFileName;


	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->fichierNom = null;
		$this->dateMaj = null;
		$this->versions = new ArrayCollection();
		$this->tempFileName = null;
		$this->fixturesDeactivate = false;
		$this->thumbFichierNom = null;
		$this->fichierThumbExt = 'png';
		$this->aFileName = null;
		$this->tempFilename = array();
		// initialisation du nom du fichier
		$this->getAFileName();
	}

	/**
	 * initialisation du nom du fichier
	 * @param string $ext - extentions du fichier ("pdf" par défaut)
	 * @return string
	 */
	private function getAFileName($ext = "pdf", $force = false) {
		if(($this->aFileName === null) || ($force === true)) {
			$date = new \Datetime();
			$this->aFileName = md5(rand(100000, 999999))."-".$date->getTimestamp();
		}
		return $this->aFileName.".".$ext;
	}

	public function setFile(UploadedFile $file) {
		$this->file = $file;
		$this->fichierExt = $this->file->guessExtension();
		if($this->fichierNom !== null) {
			// un fichier existe déjà
			$this->tempFilename['pdf'] = $this->getFichierNom();
			$this->tempFilename['png'] = $this->getThumbFichierNom();
			// nouveau $nom fichier PDF
			$this->setFichierNom($this->getAFileName($this->fichierExt));
			// thumb
			$this->setThumbFichierNom($this->getAFileName($this->fichierThumbExt));
		}
	}

	/**
	 * Génération et enregistrement du thumb, au format PNG
	 * @return boolean
	 */
	public function createThumb() {
		$newPDF = $this->getUploadRootDir().$this->getFichierNom();
		if(file_exists($newPDF)) {
			// si le fichier PDF existe, bien sûr…
			$image = new \imagick($newPDF);
			$count = $image->getNumberImages();
			$image->thumbnailImage(400);
			$image->setCompression(\imagick::COMPRESSION_LZW);
			$image->setCompressionQuality(90);
			$image->writeImage($this->getUploadRootDir().$this->getThumbFichierNom());
		}
	}

	/**
	 * Vérifie si un thumb existe (PNG)
	 * @return boolean
	 */
	public function hasThumb() {
		if(file_exists($this->getUploadRootDir().$this->getThumbFichierNom())) return true;
		else return false;
	}

	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function preUpload() {
		if($this->file === null) return;
		$this->fichierExt = $this->file->guessExtension();
		$this->fichierOrigine = $this->file->getClientOriginalName();
		$this->tailleMo = filesize($this->file);
		// nom fichier PDF
		$this->setFichierNom($this->getAFileName($this->fichierExt));
		// thumb
		$this->setThumbFichierNom($this->getAFileName($this->fichierThumbExt));
	}

	/**
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function upload() {
		if($this->file === null) return;
		if(count($this->tempFilename) > 0) {
			foreach($this->tempFilename as $fileNom) if((trim($fileNom)."") !== "") {
				$oldFile = $this->getUploadRootDir().$fileNom;
				if(file_exists($oldFile)) unlink($oldFile);
			}
		}
		$this->file->move(
			$this->getUploadRootDir(),
			$this->fichierNom
		);
		// création du thumb
		$this->createThumb();
	}

	/**
	 * @ORM\PreRemove()
	 */
	public function preRemoveUpload() {
		$this->tempFilename['pdf'] = $this->getUploadRootDir().$this->getFichierNom();
		$this->tempFilename['png'] = $this->getUploadRootDir().$this->getThumbFichierNom();
	}

	/**
	 * @ORM\PostRemove()
	 */
	public function removeUpload() {
		foreach($this->tempFilename as $tempFilename) {
			$oldFile = $this->getUploadRootDir().$tempFilename;
			if(file_exists($oldFile)) unlink($oldFile);
		}
	}

	protected function getUploadDir() {
		return "images/pdf/";
	}
	protected function getUploadRootDir() {
		return __DIR__.'/../../../../../../../web/'.$this->getUploadDir();
	}

	public function getWebPath() {
		return $this->getUploadDir().$this->getFichierNom();
	}
	public function getPdfWebPath() {
		return $this->getUploadDir().$this->getFichierNom();
	}
	public function getThumbWebPath() {
		return $this->getUploadDir().$this->getThumbFichierNom();
	}
	public function getPngWebPath() {
		return $this->getUploadDir().$this->getThumbFichierNom();
	}

	/**
	 * Set nom
	 *
	 * @param string $nom
	 * @return fichierPdf
	 */
	public function setNom($nom = null) {
		$this->nom = $nom;
		if($this->nom === null) $this->nom = $this->getFichierNom();
	
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
	 * @return fichierPdf
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
	 * Get file
	 *
	 * @return integer 
	 */
	public function getFile() {
		return $this->file;
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
	 * @return fichierPdf
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
	 * Set fichierOrigine
	 *
	 * @param string $fichierOrigine
	 * @return fichierPdf
	 */
	public function setFichierOrigine($fichierOrigine) {
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
	 * @return fichierPdf
	 */
	public function setFichierNom($fichierNom) {
		$this->fichierNom = $fichierNom;
		if($this->getNom() === null) $this->setNom($this->fichierNom);
	
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
	 * Set thumbFichierNom
	 *
	 * @param string $thumbFichierNom
	 * @return fichierPdf
	 */
	public function setThumbFichierNom($thumbFichierNom = null) {
		$this->thumbFichierNom = $thumbFichierNom;
	
		return $this;
	}

	/**
	 * Get thumbFichierNom
	 *
	 * @return string 
	 */
	public function getThumbFichierNom() {
		return $this->thumbFichierNom;
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
	 * Set fixturesDeactivate
	 *
	 */
	public function setFixturesDeactivate($on = true) {
		$this->fixturesDeactivate = $on;
		return $this;
	}

	/**
	 * Get fixturesDeactivate
	 *
	 */
	public function getFixturesDeactivate() {
		return $this->fixturesDeactivate;
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
	 * @param \AcmeGroup\LaboBundle\Entity\version $versions
	 * @return image
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

}