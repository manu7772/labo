<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
// use Gedmo\Mapping\Annotation as Gedmo;

/**
 * voteArticleBlack
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\voteArticleBlackRepository")
 */
class voteArticleBlack {

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
	 * @ORM\Column(name="adresseIp", type="string", length=24)
	 */
	private $adresseIp;

	/**
	 * @ORM\ManyToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\article", inversedBy="voteBlacks", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	private $article;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="note", type="smallint")
	 */
	private $note;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateCreation", type="datetime")
	 */
	private $dateCreation;

	/**
	 * @ORM\OneToOne(targetEntity="labo\Bundle\TestmanuBundle\Entity\richtext", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $richtext;


	public function __construct() {
		$this->dateCreation = new \Datetime();
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
	 * Set adresseIp
	 *
	 * @param string $adresseIp
	 * @return voteArticleBlack
	 */
	public function setAdresseIp($adresseIp) {
		$this->adresseIp = $adresseIp;
	
		return $this;
	}

	/**
	 * Get adresseIp
	 *
	 * @return string 
	 */
	public function getAdresseIp() {
		return $this->adresseIp;
	}

	/**
	 * Set article
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\article $article
	 * @return voteArticleBlack
	 */
	public function setArticle(\labo\Bundle\TestmanuBundle\Entity\article $article) {
		$this->article = $article;
		$article->addVoteblack($this);
	
		return $this;
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
	 * Set note
	 *
	 * @param integer $note
	 * @return voteArticleBlack
	 */
	public function setNote($note) {
		$this->note = $note;
	
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
	 * @return voteArticleBlack
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
	 * Set richtext
	 *
	 * @param integer $richtext
	 * @return voteArticleBlack
	 */
	public function setRichtext($richtext) {
		$this->richtext = $richtext;
	
		return $this;
	}

	/**
	 * Get richtext
	 *
	 * @return integer 
	 */
	public function getRichtext() {
		return $this->richtext;
	}
}
