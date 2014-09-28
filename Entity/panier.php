<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * panier
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\panierRepository")
 */
class panier {

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="paniers")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $propUser;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\article")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $article;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="quantite", type="integer", nullable=false, unique=false)
	 */
	private $quantite;

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



	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->dateMaj = null;
		$this->quantite = 0;
	}



	/**
	 * Set propUser
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $propUser
	 * @return panier
	 */
	public function setPropUser(\AcmeGroup\UserBundle\Entity\User $propUser) {
		$this->propUser = $propUser;
		$propUser->addPanier($this);

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
	 * Set article
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\article $article
	 * @return panier
	 */
	public function setArticle(\labo\Bundle\TestmanuBundle\Entity\article $article) {
		$this->article = $article;
	
		return $this;
	}

	/**
	 * Get prixtotal
	 *
	 * @return float
	 */
	public function getPrixtotal() {
		if($this->article->getPrix() !== null) return ($this->article->getPrix() * $this->quantite);
		else return 0;
	}

	/**
	 * Get getPrixtotaltxt
	 *
	 * @return string
	 */
	public function getPrixtotaltxt() {
		return number_format($this->getPrixtotal(), 2, ",", "");
	}

	/**
	 * Get article
	 *
	 * @return \labo\Bundle\TestmanuBundle\Entity\article 
	 */
	public function getArticle() {
		return $this->article;
	}

	/**
	 * Set quantite
	 *
	 * @param integer $quantite
	 * @return panier
	 */
	public function setQuantite($quantite) {
		$this->quantite = $quantite;
		return $this;
	}

	/**
	 * ajouteQuantite
	 *
	 * @param integer $quantite
	 * @return panier
	 */
	public function ajouteQuantite($quantite) {
		$this->quantite += $quantite;
		return $this;
	}

	/**
	 * retireQuantite
	 *
	 * @param integer $quantite
	 * @return panier
	 */
	public function retireQuantite($quantite) {
		$this->quantite -= $quantite;
		if($this->quantite < 0) $this->quantite = 0;
		return $this;
	}

	/**
	 * Get quantite
	 *
	 * @return integer 
	 */
	public function getQuantite() {
		return $this->quantite;
	}

	/**
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return article
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
	 * Set version
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\version $version
	 * @return article
	 */
	public function setVersion(\labo\Bundle\TestmanuBundle\Entity\version $version) {
		$this->version = $version;
	
		return $this;
	}

	/**
	 * Get version
	 *
	 * @return \labo\Bundle\TestmanuBundle\Entity\version $version
	 */
	public function getVersion() {
		return $this->version;
	}



}
