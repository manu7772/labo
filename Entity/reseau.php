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
abstract class reseau {

	protected $id;

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
	protected $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true, unique=false)
	 */
	protected $descriptif;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $statut;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	protected $slug;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\article", mappedBy="reseaus")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $articles;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="couleurFond", type="string", length=30, nullable=true, unique=false)
	 */
	protected $couleurFond;


	public function __construct() {
		$this->articles = new ArrayCollection();
		$this->couleurFond = "#FFFFFF";
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
	 * @return article
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
	 * @return article
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
	 * @param \AcmeGroup\LaboBundle\Entity\article $article
	 * @return reseau
	 */
	public function addArticle(\AcmeGroup\LaboBundle\Entity\article $article) {
		$this->articles[] = $article;
		$article->addBdirReseau($this);
	
		return $this;
	}

	/**
	 * Remove article
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $article
	 */
	public function removeArticle(\AcmeGroup\LaboBundle\Entity\article $article) {
		$this->articles->removeElement($article);
		$article->removeBdirReseau($this);
	}

	/**
	 * Add BdirArticle -----> pour bidirectionnel bilatéral
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $article
	 * @return reseau
	 */
	public function addBdirArticle(\AcmeGroup\LaboBundle\Entity\article $article) {
		$this->articles[] = $article;
	
		return $this;
	}

	/**
	 * Remove BdirArticle -----> pour bidirectionnel bilatéral
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $article
	 */
	public function removeBdirArticle(\AcmeGroup\LaboBundle\Entity\article $article) {
		$this->articles->removeElement($article);
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


}
