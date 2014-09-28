<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * commande
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\commandeRepository")
 */
class commande {
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
	 * @ORM\Column(name="reference", type="string", length=255)
	 */
	private $reference;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="user", type="integer")
	 */
	private $user;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="produits", type="integer")
	 */
	private $produits;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateMaj", type="datetime")
	 */
	private $dateMaj;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateCreation", type="datetime", nullable=false)
	 */
	private $dateCreation;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="commandes")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $propUser;


	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->dateMaj = null;
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
	 * Set reference
	 *
	 * @param string $reference
	 * @return commande
	 */
	public function setReference($reference) {
		$this->reference = $reference;
	
		return $this;
	}

	/**
	 * Get reference
	 *
	 * @return string 
	 */
	public function getReference() {
		return $this->reference;
	}

	/**
	 * Set user
	 *
	 * @param integer $user
	 * @return commande
	 */
	public function setUser($user) {
		$this->user = $user;
	
		return $this;
	}

	/**
	 * Get user
	 *
	 * @return integer 
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Set produits
	 *
	 * @param integer $produits
	 * @return commande
	 */
	public function setProduits($produits) {
		$this->produits = $produits;
	
		return $this;
	}

	/**
	 * Get produits
	 *
	 * @return integer 
	 */
	public function getProduits() {
		return $this->produits;
	}

	/**
	 * Set dateMaj
	 *
	 * @param \DateTime $dateMaj
	 * @return commande
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
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return commande
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
	 * Set propUser
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $propUser
	 * @return commande
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
}