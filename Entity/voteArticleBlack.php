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
abstract class voteArticleBlack {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="adresseIp", type="string", length=24)
	 */
	protected $adresseIp;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\article", inversedBy="voteBlacks", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
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
	 * @ORM\OneToOne(targetEntity="AcmeGroup\LaboBundle\Entity\richtext", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $richtext;


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
	 * @param \AcmeGroup\LaboBundle\Entity\article $article
	 * @return voteArticleBlack
	 */
	public function setArticle(\AcmeGroup\LaboBundle\Entity\article $article) {
		$this->article = $article;
		$article->addVoteblack($this);
	
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
