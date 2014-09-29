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
abstract class bonLivraison {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="reference", type="string", length=100)
	 */
	protected $reference;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateCreation", type="datetime", nullable=false)
	 */
	protected $dateCreation;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateMaj", type="datetime", nullable=true)
	 */
	protected $dateMaj;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", inversedBy="bonLivraisons")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $client;


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
	 * @return bonLivraison
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
	 * Set client
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $client
	 * @return bonLivraison
	 */
	public function setClient(\AcmeGroup\UserBundle\Entity\User $client = null) {
		$this->client = $client;
	
		return $this;
	}

	/**
	 * Get client
	 *
	 * @return \AcmeGroup\UserBundle\Entity\User 
	 */
	public function getClient() {
		return $this->client;
	}
}