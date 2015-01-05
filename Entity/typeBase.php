<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;
use labo\Bundle\TestmanuBundle\Entity\entityBase;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class typeBase extends entityBase {

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=100, nullable=false, unique=true)
	 * @Assert\NotBlank(message = "Vous devez remplir ce champ.")
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
	 * @ORM\Column(name="nomcourt", type="string", length=3, nullable=true, unique=false)
	 * @Assert\NotBlank(message = "Vous devez remplir ce champ.")
	 * @Assert\Length(
	 *      min = "1",
	 *      max = "3",
	 *      minMessage = "Le nom court doit comporter au moins {{ limit }} lettres.",
	 *      maxMessage = "Le nom court doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	protected $nomcourt;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true, unique=false)
	 */
	protected $descriptif;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	protected $slug;

	// nombre de lettre max pour $nomcourt
	protected $lengtNomCourt;

	public function __construct() {
		parent::__construct();
		$this->lengtNomCourt = 3;
		$this->nomcourt = null;
	}

	public function __call($name, $arguments = null) {
		switch ($name) {
			case 'isType':
				return true;
				break;
			case 'isEntityBase':
				return false;
				break;
			
			default:
				return false;
				break;
		}
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
	 * @return type
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
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function defineNomCourt() {
        if($this->nomcourt === null) $this->nomcourt = substr($this->nom, 0, $this->lengtNomCourt);
    }

	/**
	 * Set nomcourt
	 *
	 * @param string $nomcourt
	 * @return type
	 */
	public function setNomcourt($nomcourt = null) {
		if(is_string($nomcourt)) {
			$this->nomcourt = substr($nomcourt, 0, $this->lengtNomCourt);
		} else $this->nomcourt = null;
	
		return $this;
	}

	/**
	 * Get nomcourt
	 *
	 * @return string 
	 */
	public function getNomcourt() {
		return $this->nomcourt;
	}

	/**
	 * Set descriptif
	 *
	 * @param string $descriptif
	 * @return type
	 */
	public function setDescriptif($descriptif = null) {
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
     * Set slug
     *
     * @param integer $slug
     * @return type
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


}
