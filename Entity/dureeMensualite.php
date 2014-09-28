<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * dureeMensualite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\dureeMensualiteRepository")
 * @UniqueEntity(fields={"nom", "nbMois"}, message="Cette durée existe déjà.")
 */
class dureeMensualite {
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
	 * @ORM\Column(name="nom", type="string", length=100, nullable=false, unique=true)
	 * @Assert\NotBlank(message = "Vous devez remplir ce champ.")
	 * @Assert\Length(
	 *      min = "3",
	 *      max = "100",
	 *      minMessage = "Le nom doit comporter au moins {{ limit }} lettres.",
	 *      maxMessage = "Le nom doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	private $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true)
	 */
	private $descriptif;

	/**
	 * @var integer
	 *
	 * @Assert\NotBlank(message = "Vous devez indiquer une durée (en mois).")
	 * @ORM\Column(name="nbMois", type="integer", nullable=false, unique=true)
	 */
	private $nbMois;



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
	 * @return dureeMensualite
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
	 * Set descriptif
	 *
	 * @param string $descriptif
	 * @return dureeMensualite
	 */
	public function setDescriptif($descriptif) {
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
	 * Set nbMois
	 *
	 * @param integer $nbMois
	 * @return dureeMensualite
	 */
	public function setNbMois($nbMois) {
		$this->nbMois = $nbMois;
	
		return $this;
	}

	/**
	 * Get nbMois
	 *
	 * @return integer 
	 */
	public function getNbMois() {
		return $this->nbMois;
	}
}
