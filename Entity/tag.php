<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\tagRepository")
 * @UniqueEntity(fields={"nom"}, message="Ce tag existe déjà.")
 */
class tag {
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
	 * @Assert\NotBlank(message = "Vous devez nommer ce tag")
	 * @Assert\Length(
	 *      min = "3",
	 *      max = "50",
	 *      minMessage = "Le tag doit comporter au moins {{ limit }} lettres.",
	 *      maxMessage = "Le tag doit comporter au maximum {{ limit }} lettres."
	 * )
	 */
	private $nom;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\richtext", mappedBy="tags")
	 */
	private $richtexts;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\pageweb", mappedBy="tags")
	 */
	private $pagewebs;

	/**
	 * @Gedmo\Slug(fields={"nom"})
	 * @ORM\Column(length=128, unique=true)
	 */
	private $slug;


	public function __construct() {
		$this->richtexts = new ArrayCollection();
		$this->pagewebs = new ArrayCollection();
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
	 * @return baseEntity
	 */
	public function setNom($nom) {
		$nom = str_replace(" ", '-', trim($nom));
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
	 * Add richtext
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\richtext $richtext
	 * @return tag
	 */
	public function addRichtext(\labo\Bundle\TestmanuBundle\Entity\richtext $richtext) {
		$this->richtexts->add($richtext);
	
		return $this;
	}

	/**
	 * Remove richtext
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\richtext $richtext
	 */
	public function removeRichtext(\labo\Bundle\TestmanuBundle\Entity\richtext $richtext) {
		$this->richtexts->removeElement($richtext);
	}

	/**
	 * Get richtexts
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getRichtexts() {
		return $this->richtexts;
	}

	/**
	 * Add pageweb
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\pageweb $pageweb
	 * @return tag
	 */
	public function addPageweb(\labo\Bundle\TestmanuBundle\Entity\pageweb $pageweb) {
		$this->pagewebs->add($pageweb);
	
		return $this;
	}

	/**
	 * Remove pageweb
	 *
	 * @param \labo\Bundle\TestmanuBundle\Entity\pageweb $pageweb
	 */
	public function removePageweb(\labo\Bundle\TestmanuBundle\Entity\pageweb $pageweb) {
		$this->pagewebs->removeElement($pageweb);
	}

	/**
	 * Get pagewebs
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getPagewebs() {
		return $this->pagewebs;
	}

	/**
	 * Set slug
	 *
	 * @param integer $slug
	 * @return baseEntity
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
