<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Tree
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * parametre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="labo\Bundle\TestmanuBundle\Entity\parametreRepository")
 * @UniqueEntity(fields={"nom"}, message="Ce paramètre existe déjà")
 */
class parametre {

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
     * @ORM\Column(name="nom", type="string", length=100, nullable=false, unique=false)
     * @Assert\NotBlank(message = "Vous devez nommer cet artible.")
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
     * @ORM\Column(name="valeur", type="string", length=255, nullable=false, unique=false)
     */
    private $valeur;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=false, unique=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="groupe", type="integer", nullable=false, unique=false)
     */
    private $groupe;

    /**
     * @var string
     *
     * @ORM\Column(name="optionlist", type="text", nullable=true, unique=false)
     */
    private $optionList;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptif", type="text", nullable=true, unique=false)
     */
    private $descriptif;

    /**
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="labo\Bundle\TestmanuBundle\Entity\version")
     */
    private $versions;

    private $listTypes = array("Texte", "Valeur", "On/off", "Choix");
    private $listGroupes = array("Alertes", "Accueil");

    public function __construct() {
        $this->versions = new ArrayCollection();
        // $this->listTypes;
        $this->valeur = null;
        $this->optionList = null;
        $this->type = 0;
        $this->groupe = 0;
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
     * @return parametre
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
     * Set type
     *
     * @param string $type
     * @return parametre
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Get listTypes
     *
     * @return string 
     */
    public function getListTypes() {
        return $this->listTypes;
    }

    /**
     * Set groupe
     *
     * @param string $groupe
     * @return parametre
     */
    public function setGroupe($groupe) {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return string 
     */
    public function getGroupe() {
        return $this->groupe;
    }

    /**
     * Get listGroupes
     *
     * @return string 
     */
    public function getListGroupes() {
        return $this->listGroupes;
    }

    /**
     * Set valeur
     *
     * @param string $valeur
     * @return parametre
     */
    public function setValeur($valeur) {
        switch($this->getType()) {
            case 1: // valeur
                $this->valeur = $valeur."";
                break;
            case 2: // on/off
                if(intval($valeur) > 0) $this->valeur = 1;
                    else $this->valeur = 0;
                break;
            case 3: // choix
                $this->valeur = $valeur;
                break;
            default: // texte, etc.
                $this->valeur = $valeur;
                break;
        }
        return $this;
    }

    /**
     * Get valeur
     *
     * @return string 
     */
    public function getValeur() {
        switch($this->getType()) {
            case 1: // valeur
                return intval($this->valeur);
                break;
            case 2: // on/off
                if(intval($this->valeur) > 0) return true;
                    else return false;
                break;
            case 3: // choix
                return $this->valeur;
                break;
            default: // texte, etc.
                return $this->valeur;
                break;
        }
    }

    /**
     * Set optionList
     *
     * @param string $optionList
     * @return parametre
     */
    public function setOptionList($optionList = null) {
        $this->optionList = $optionList;

        return $this;
    }

    /**
     * Get OptionList
     * 
     * @return string
     */
    public function getOptionList() {
        return $this->optionList;
    }

    /**
     * Get OptionList as array
     * 
     * @return array
     */
    public function getOptionListAsArray() {
        return explode("|", $this->optionList);
    }

    /**
     * Set descriptif
     *
     * @param string $descriptif
     * @return parametre
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
     * Add version
     *
     * @param \labo\Bundle\TestmanuBundle\Entity\version $version
     * @return parametre
     */
    public function addVersion(\labo\Bundle\TestmanuBundle\Entity\version $version) {
        $this->versions[] = $version;
    
        return $this;
    }

    /**
     * Remove version
     *
     * @param \labo\Bundle\TestmanuBundle\Entity\version $version
     */
    public function removeVersion(\labo\Bundle\TestmanuBundle\Entity\version $version) {
        $this->versions->removeElement($version);
    }

    /**
     * Get versions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVersions() {
        return $this->versions;
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
