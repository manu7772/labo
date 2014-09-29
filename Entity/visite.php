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
abstract class visite {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ip", type="string", length=32)
	 */
	protected $ip;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="User", type="integer", nullable=true)
	 */
	protected $user;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="systeme", type="string", length=32, nullable=true)
	 */
	protected $systeme;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="navigateur", type="string", length=32, nullable=true)
	 */
	protected $navigateur;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="url", type="string", length=255, nullable=true)
	 */
	protected $url;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="route", type="string", length=255, nullable=true)
	 */
	protected $route;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="get", type="string", length=255, nullable=true)
	 */
	protected $get;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateCreation", type="datetime")
	 */
	protected $dateCreation;


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
	 * Set ip
	 *
	 * @param string $ip
	 * @return visite
	 */
	public function setIp($ip) {
		$this->ip = $ip;
	
		return $this;
	}

	/**
	 * Get ip
	 *
	 * @return string 
	 */
	public function getIp() {
		return $this->ip;
	}

	/**
	 * Set user
	 *
	 * @param integer $user
	 * @return visite
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
	 * Set systeme
	 *
	 * @param string $systeme
	 * @return visite
	 */
	public function setSysteme($systeme) {
		$this->systeme = $systeme;
	
		return $this;
	}

	/**
	 * Get systeme
	 *
	 * @return string 
	 */
	public function getSysteme() {
		return $this->systeme;
	}

	/**
	 * Set navigateur
	 *
	 * @param string $navigateur
	 * @return visite
	 */
	public function setNavigateur($navigateur) {
		$this->navigateur = $navigateur;
	
		return $this;
	}

	/**
	 * Get navigateur
	 *
	 * @return string 
	 */
	public function getNavigateur() {
		return $this->navigateur;
	}

	/**
	 * Set url
	 *
	 * @param string $url
	 * @return visite
	 */
	public function setUrl($url) {
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
	 * Set route
	 *
	 * @param string $route
	 * @return visite
	 */
	public function setRoute($route) {
		$this->route = $route;
	
		return $this;
	}

	/**
	 * Get route
	 *
	 * @return string 
	 */
	public function getRoute() {
		return $this->route;
	}

	/**
	 * Set get
	 *
	 * @param string $get
	 * @return visite
	 */
	public function setGet($get) {
		$this->get = $get;
	
		return $this;
	}

	/**
	 * Get get
	 *
	 * @return string 
	 */
	public function getGet() {
		return $this->get;
	}

	/**
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return visite
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
}
