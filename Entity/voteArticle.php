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
abstract class voteArticle {

	protected $user;

	protected $article;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="note", type="smallint")
	 */
	protected $note;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateCreation", type="datetime")
	 */
	protected $dateCreation;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="avis", type="text", nullable=true, unique=false)
	 */
	protected $avis;


	public function __construct() {
		$this->dateCreation = new \Datetime();
	}


	/**
	 * Set user
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $user
	 * @return voteArticle
	 */
	public function setUser(\AcmeGroup\UserBundle\Entity\User $user) {
		$this->user = $user;
		$user->addVoteArticle($this);
	
		return $this;
	}

	/**
	 * Get user
	 *
	 * @return \AcmeGroup\UserBundle\Entity\User 
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Set article
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $article
	 * @return voteArticle
	 */
	public function setArticle(\AcmeGroup\LaboBundle\Entity\article $article) {
		$this->article = $article;
		$article->addVoteUser($this);
	
		return $this;
	}

	/**
	 * Get article
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\article 
	 */
	public function getArticle() {
		return $this->article;
	}

	/**
	 * Set note
	 *
	 * @param integer $note
	 * @return voteArticle
	 */
	public function setNote($note) {
		if($note > 5) $note = 5;
		if($note < 1) $note = 1;
		$this->note = intval($note);
	
		return $this;
	}

	/**
	 * Get note
	 *
	 * @return integer 
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return voteArticle
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
	 * @return voteArticle
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
	 * Set avis
	 *
	 * @param string $avis
	 * @return article
	 */
	public function setAvis($avis) {
		$this->avis = $avis;
	
		return $this;
	}

	/**
	 * Get avis
	 *
	 * @return string 
	 */
	public function getAvis() {
		return $this->avis;
	}

}
