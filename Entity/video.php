<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * video
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\videoRepository")
 */
class video {
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
	 * @ORM\Column(name="nom", type="string", length=100, nullable=false, unique=false)
	 * @Assert\NotBlank(message = "Vous devez nommer cet artible.")
	 * @Assert\Length(
	 *      min = "3",
	 *      max = "100",
	 *      minMessage = "Le nom doit comporter au moins {{ limit }} lettres.",
	 *      maxMessage = "Le nom doit comporter au maximum {{ limit }} lettres."
	 * )
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
	 * @ORM\Column(name="videoUrl", type="text")
	 */
	private $videoUrl;

	/**
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $statut;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="rank", type="smallint", nullable=false, unique=false)
	 */
	private $rank;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="notation", type="smallint", nullable=false, unique=false)
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
	 * @ORM\Column(name="datePublication", type="datetime", nullable=false)
	 */
	private $datePublication;

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
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\version")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $versions;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	private $slug;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="videos")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $propUser;

	/**
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\article", mappedBy="videos")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $articles;


	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->datePublication = new \Datetime();
		$this->dateMaj = null;
		$this->dateExpiration = null;
		$this->rank = 0;
		$this->notation = 0;
		$this->versions = new ArrayCollection();
		$this->articles = new ArrayCollection();
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
	 * @return video
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
	 * @return video
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
	 * Set videoUrl
	 *
	 * @param string $videoUrl
	 * @return video
	 */
	public function setVideoUrl($videoUrl) {
		$this->videoUrl = $videoUrl;
	
		return $this;
	}

	/**
	 * Get videoUrl
	 *
	 * @return string 
	 */
	public function getVideoUrl() {
		return $this->videoUrl;
	}

	/**
	 * Set statut
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\statut $statut
	 * @return video
	 */
	public function setStatut(\labo\Bundle\TestmanuBundle\Entity\statut $statut) {
		$this->statut = $statut;
	
		return $this;
	}

	/**
	 * Get statut
	 *
	 * @return \labo\Bundle\TestmanuBundle\Entity\statut 
	 */
	public function getStatut() {
		return $this->statut;
	}

	/**
	 * Set rank
	 *
	 * @param integer $rank
	 * @return video
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
	 * @return video
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
	 * @return video
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
	 * Set datePublication
	 *
	 * @param \DateTime $datePublication
	 * @return video
	 */
	public function setDatePublication($datePublication) {
		$this->datePublication = $datePublication;
	
		return $this;
	}

	/**
	 * Get datePublication
	 *
	 * @return \DateTime 
	 */
	public function getDatePublication() {
		return $this->datePublication;
	}

	/**
	 * Set dateMaj
	 *
	 * @param \DateTime $dateMaj
	 * @return video
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
	 * @return video
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
	 * @param \labo\Bundle\TestmanuBundle\Entity\version $versions
	 * @return video
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
	 * Set propUser
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $propUser
	 * @return video
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
	 * Get articles
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getArticles() {
		return $this->articles;
	}

	/**
	 * Add article
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\article $article
	 * @return video
	 */
	public function addArticle(\labo\Bundle\TestmanuBundle\Entity\article $article = null) {
		$this->articles[] = $article;
		// $article->addVideo($this);
	
		return $this;
	}

	/**
	 * Remove article
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\article $article
	 */
	public function removeArticle(\labo\Bundle\TestmanuBundle\Entity\article $article = null) {
		$this->articles->removeElement($article);
		// $article->removeVideo($this);
	}

}
