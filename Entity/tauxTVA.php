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
 * @UniqueEntity(fields={"nom"}, message="Ce taux de tva existe déjà")
 */
abstract class tauxTVA {

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

	protected $nomlong;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true)
	 */
	protected $descriptif;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="taux", type="float", nullable=false, unique=true)
	 */
	protected $taux;



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
	 * @return baseEntity
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
	 * Get nom
	 *
	 * @return string 
	 */
	public function getNomlong() {
		return $this->getTaux(). "% (".$this->getNom().")";
	}

	/**
	 * Set descriptif
	 *
	 * @param string $descriptif
	 * @return baseEntity
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
	 * Set taux
	 *
	 * @param float $taux
	 * @return tauxTVA
	 */
	public function setTaux($taux) {
		$this->taux = $taux;
	
		return $this;
	}

	/**
	 * Get taux
	 *
	 * @return float 
	 */
	public function getTaux() {
		return $this->taux;
	}
}
