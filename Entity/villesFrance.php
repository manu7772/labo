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
 * @UniqueEntity(fields={"nom"}, message="Cette ville existe déjà")
 */
abstract class villesFrance {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="departement", type="string", length=8)
	 */
	protected $departement;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=255)
	 */
	protected $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nomReel", type="string", length=255)
	 */
	protected $nomReel;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nomSoundex", type="string", length=255)
	 */
	protected $nomSoundex;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nomMetaphone", type="string", length=255)
	 */
	protected $nomMetaphone;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="codePostal", type="string", length=8)
	 */
	protected $codePostal;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="commune", type="integer")
	 */
	protected $commune;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="codeCommune", type="string", length=16)
	 */
	protected $codeCommune;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="arrondissement", type="integer")
	 */
	protected $arrondissement;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="canton", type="integer")
	 */
	protected $canton;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="amdi", type="integer")
	 */
	protected $amdi;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="surface", type="integer")
	 */
	protected $surface;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="longitudeDeg", type="string", length=255)
	 */
	protected $longitudeDeg;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="latitudeDeg", type="string", length=255)
	 */
	protected $latitudeDeg;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="longitudeGrd", type="string", length=255)
	 */
	protected $longitudeGrd;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="latitudeGrd", type="string", length=255)
	 */
	protected $latitudeGrd;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="longitudeDms", type="string", length=255)
	 */
	protected $longitudeDms;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="latitudeDms", type="string", length=255)
	 */
	protected $latitudeDms;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="zmin", type="integer")
	 */
	protected $zmin;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="zmax", type="integer")
	 */
	protected $zmax;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="surfaceOrderFrance", type="integer")
	 */
	protected $surfaceOrderFrance;


	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set departement
	 *
	 * @param string $departement
	 * @return villesFrance
	 */
	public function setDepartement($departement) {
		$this->departement = $departement;
	
		return $this;
	}

	/**
	 * Get departement
	 *
	 * @return string 
	 */
	public function getDepartement() {
		return $this->departement;
	}

	/**
	 * Set nom
	 *
	 * @param string $nom
	 * @return villesFrance
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
	 * Set nomReel
	 *
	 * @param string $nomReel
	 * @return villesFrance
	 */
	public function setNomReel($nomReel) {
		$this->nomReel = $nomReel;
	
		return $this;
	}

	/**
	 * Get nomReel
	 *
	 * @return string 
	 */
	public function getNomReel() {
		return $this->nomReel;
	}

	/**
	 * Set nomSoundex
	 *
	 * @param string $nomSoundex
	 * @return villesFrance
	 */
	public function setNomSoundex($nomSoundex) {
		$this->nomSoundex = $nomSoundex;
	
		return $this;
	}

	/**
	 * Get nomSoundex
	 *
	 * @return string 
	 */
	public function getNomSoundex() {
		return $this->nomSoundex;
	}

	/**
	 * Set nomMetaphone
	 *
	 * @param string $nomMetaphone
	 * @return villesFrance
	 */
	public function setNomMetaphone($nomMetaphone) {
		$this->nomMetaphone = $nomMetaphone;
	
		return $this;
	}

	/**
	 * Get nomMetaphone
	 *
	 * @return string 
	 */
	public function getNomMetaphone() {
		return $this->nomMetaphone;
	}

	/**
	 * Set codePostal
	 *
	 * @param string $codePostal
	 * @return villesFrance
	 */
	public function setCodePostal($codePostal) {
		$this->codePostal = $codePostal;
	
		return $this;
	}

	/**
	 * Get codePostal
	 *
	 * @return string 
	 */
	public function getCodePostal() {
		return $this->codePostal;
	}

	/**
	 * Set commune
	 *
	 * @param integer $commune
	 * @return villesFrance
	 */
	public function setCommune($commune) {
		$this->commune = $commune;
	
		return $this;
	}

	/**
	 * Get commune
	 *
	 * @return integer 
	 */
	public function getCommune() {
		return $this->commune;
	}

	/**
	 * Set codeCommune
	 *
	 * @param string $codeCommune
	 * @return villesFrance
	 */
	public function setCodeCommune($codeCommune) {
		$this->codeCommune = $codeCommune;
	
		return $this;
	}

	/**
	 * Get codeCommune
	 *
	 * @return string 
	 */
	public function getCodeCommune() {
		return $this->codeCommune;
	}

	/**
	 * Set arrondissement
	 *
	 * @param integer $arrondissement
	 * @return villesFrance
	 */
	public function setArrondissement($arrondissement) {
		$this->arrondissement = $arrondissement;
	
		return $this;
	}

	/**
	 * Get arrondissement
	 *
	 * @return integer 
	 */
	public function getArrondissement() {
		return $this->arrondissement;
	}

	/**
	 * Set canton
	 *
	 * @param integer $canton
	 * @return villesFrance
	 */
	public function setCanton($canton) {
		$this->canton = $canton;
	
		return $this;
	}

	/**
	 * Get canton
	 *
	 * @return integer 
	 */
	public function getCanton() {
		return $this->canton;
	}

	/**
	 * Set amdi
	 *
	 * @param integer $amdi
	 * @return villesFrance
	 */
	public function setAmdi($amdi) {
		$this->amdi = $amdi;
	
		return $this;
	}

	/**
	 * Get amdi
	 *
	 * @return integer 
	 */
	public function getAmdi() {
		return $this->amdi;
	}

	/**
	 * Set surface
	 *
	 * @param integer $surface
	 * @return villesFrance
	 */
	public function setSurface($surface) {
		$this->surface = $surface;
	
		return $this;
	}

	/**
	 * Get surface
	 *
	 * @return integer 
	 */
	public function getSurface() {
		return $this->surface;
	}

	/**
	 * Set longitudeDeg
	 *
	 * @param string $longitudeDeg
	 * @return villesFrance
	 */
	public function setLongitudeDeg($longitudeDeg) {
		$this->longitudeDeg = $longitudeDeg;
	
		return $this;
	}

	/**
	 * Get longitudeDeg
	 *
	 * @return string 
	 */
	public function getLongitudeDeg() {
		return $this->longitudeDeg;
	}

	/**
	 * Set latitudeDeg
	 *
	 * @param string $latitudeDeg
	 * @return villesFrance
	 */
	public function setLatitudeDeg($latitudeDeg) {
		$this->latitudeDeg = $latitudeDeg;
	
		return $this;
	}

	/**
	 * Get latitudeDeg
	 *
	 * @return string 
	 */
	public function getLatitudeDeg() {
		return $this->latitudeDeg;
	}

	/**
	 * Set longitudeGrd
	 *
	 * @param string $longitudeGrd
	 * @return villesFrance
	 */
	public function setLongitudeGrd($longitudeGrd) {
		$this->longitudeGrd = $longitudeGrd;
	
		return $this;
	}

	/**
	 * Get longitudeGrd
	 *
	 * @return string 
	 */
	public function getLongitudeGrd() {
		return $this->longitudeGrd;
	}

	/**
	 * Set latitudeGrd
	 *
	 * @param string $latitudeGrd
	 * @return villesFrance
	 */
	public function setLatitudeGrd($latitudeGrd) {
		$this->latitudeGrd = $latitudeGrd;
	
		return $this;
	}

	/**
	 * Get latitudeGrd
	 *
	 * @return string 
	 */
	public function getLatitudeGrd() {
		return $this->latitudeGrd;
	}

	/**
	 * Set longitudeDms
	 *
	 * @param string $longitudeDms
	 * @return villesFrance
	 */
	public function setLongitudeDms($longitudeDms) {
		$this->longitudeDms = $longitudeDms;
	
		return $this;
	}

	/**
	 * Get longitudeDms
	 *
	 * @return string 
	 */
	public function getLongitudeDms() {
		return $this->longitudeDms;
	}

	/**
	 * Set latitudeDms
	 *
	 * @param string $latitudeDms
	 * @return villesFrance
	 */
	public function setLatitudeDms($latitudeDms) {
		$this->latitudeDms = $latitudeDms;
	
		return $this;
	}

	/**
	 * Get latitudeDms
	 *
	 * @return string 
	 */
	public function getLatitudeDms() {
		return $this->latitudeDms;
	}

	/**
	 * Set zmin
	 *
	 * @param integer $zmin
	 * @return villesFrance
	 */
	public function setZmin($zmin) {
		$this->zmin = $zmin;
	
		return $this;
	}

	/**
	 * Get zmin
	 *
	 * @return integer 
	 */
	public function getZmin() {
		return $this->zmin;
	}

	/**
	 * Set zmax
	 *
	 * @param integer $zmax
	 * @return villesFrance
	 */
	public function setZmax($zmax) {
		$this->zmax = $zmax;
	
		return $this;
	}

	/**
	 * Get zmax
	 *
	 * @return integer 
	 */
	public function getZmax() {
		return $this->zmax;
	}

	/**
	 * Set surfaceOrderFrance
	 *
	 * @param integer $surfaceOrderFrance
	 * @return villesFrance
	 */
	public function setSurfaceOrderFrance($surfaceOrderFrance) {
		$this->surfaceOrderFrance = $surfaceOrderFrance;
	
		return $this;
	}

	/**
	 * Get surfaceOrderFrance
	 *
	 * @return integer 
	 */
	public function getSurfaceOrderFrance() {
		return $this->surfaceOrderFrance;
	}
}
