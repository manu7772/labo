<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * pays
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\paysRepository")
 * @UniqueEntity(fields={"nom"}, message="Ce pays est déjà enregistré")
 */
class pays {
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
	 */
	private $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descriptif", type="text", nullable=true, unique=false)
	 */
	private $descriptif;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="sigle", type="string", length=5, nullable=false, unique=true)
	 * @Assert\NotBlank(message = "Vous devez remplir ce champ.")
	 * @Assert\Length(
	 *      min = "1",
	 *      max = "3",
	 *      minMessage = "Le nom doit comporter au moins {{ limit }} lettres.",
	 *      maxMessage = "Le nom doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	private $sigle;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="capitale", type="string", length=100, nullable=true, unique=true)
	 */
	private $capitale;



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
	 * @return pays
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
	 * Set sigle
	 *
	 * @param string $sigle
	 * @return pays
	 */
	public function setSigle($sigle) {
		$this->sigle = $sigle;
	
		return $this;
	}

	/**
	 * Get sigle
	 *
	 * @return string 
	 */
	public function getSigle() {
		return $this->sigle;
	}

	/**
	 * Set capitale
	 *
	 * @param string $capitale
	 * @return pays
	 */
	public function setCapitale($capitale) {
		$this->capitale = $capitale;
	
		return $this;
	}

	/**
	 * Get capitale
	 *
	 * @return string 
	 */
	public function getCapitale() {
		return $this->capitale;
	}
}

?>
