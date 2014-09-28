<?php

namespace AcmeGroup\LaboBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * tempuser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AcmeGroup\LaboBundle\Entity\tempuserRepository")
 * @UniqueEntity(fields={"username"}, message="Cet utilisateur existe déjà.")
 * @UniqueEntity(fields={"email"}, message="Cet email existe déjà.")
 */
class tempuser {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime", nullable=false)
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     * @Assert\NotBlank(message = "Vous devez donner un nom d'utilisateur.")
     * @Assert\Length(
     *      min = "3",
     *      max = "100",
     *      minMessage = "Votre nom doit comporter au moins {{ limit }} lettres.",
     *      maxMessage = "Votre nom doit comporter au maximum {{ limit }} lettres."
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(message = "Vous devez fournir une adresse mail valide.")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp1", type="string", length=255)
     * @Assert\NotBlank(message = "Vous devez entrer un mot de passe.")
     * @Assert\Length(
     *      min = "8",
     *      max = "100",
     *      minMessage = "Le mot de passe doit comporter au moins {{ limit }} lettres.",
     *      maxMessage = "Le mot de passe doit comporter au maximum {{ limit }} lettres."
     * )
     */
    private $mdp1;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp2", type="string", length=255)
     * @Assert\NotBlank(message = "Vous devez entrer un mot de passe.")
     * @Assert\Length(
     *      min = "8",
     *      max = "100",
     *      minMessage = "Le mot de passe doit comporter au moins {{ limit }} lettres.",
     *      maxMessage = "Le mot de passe doit comporter au maximum {{ limit }} lettres."
     * )
     */
    private $mdp2;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\magasin")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    private $magasin;

    /**
     * @var string
     *
     * @ORM\Column(name="modelivraison", type="string", length=100, nullable=false, unique=false)
     */
    private $modelivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true, unique=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true, unique=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true, unique=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text", nullable=true, unique=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=12, nullable=true, unique=false)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true, unique=false)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="typemachine", type="string", length=255, nullable=true, unique=false)
     */
    private $typemachine;

    /**
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\marque")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    private $marque;

    /**
     * @var string
     *
     * @ORM\Column(name="numserie", type="string", length=255, nullable=true, unique=false)
     */
    private $numserie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateachat", type="datetime", nullable=true, unique=false)
     */
    private $dateachat;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\version")
     */
    private $versions;


    public function __construct() {
        $this->dateCreation = new \Datetime();
        $this->versions = new ArrayCollection();
        $this->modelivraison = "poste";
    }

    /**
     * @Assert\True(message = "Les mots de passe ne sont pas identiques.")
     */
    public function isPasswordValid() {
        if($this->mdp1 === $this->mdp2) return true;
        else return false;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return tempuser
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
     * Set username
     *
     * @param string $username
     * @return tempuser
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return tempuser
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set mdp1
     *
     * @param string $mdp1
     * @return tempuser
     */
    public function setMdp1($mdp1)
    {
        $this->mdp1 = $mdp1;
    
        return $this;
    }

    /**
     * Get mdp1
     *
     * @return string 
     */
    public function getMdp1()
    {
        return $this->mdp1;
    }

    /**
     * Set mdp2
     *
     * @param string $mdp2
     * @return tempuser
     */
    public function setMdp2($mdp2)
    {
        $this->mdp2 = $mdp2;
    
        return $this;
    }

    /**
     * Get mdp2
     *
     * @return string 
     */
    public function getMdp2()
    {
        return $this->mdp2;
    }

    /**
     * Set magasin
     *
     * @param \stdClass $magasin
     * @return tempuser
     */
    public function setMagasin($magasin)
    {
        $this->magasin = $magasin;
    
        return $this;
    }

    /**
     * Get magasin
     *
     * @return \stdClass 
     */
    public function getMagasin()
    {
        return $this->magasin;
    }

    /**
     * Set modelivraison
     *
     * @param string $modelivraison
     * @return tempuser
     */
    public function setModelivraison($modelivraison)
    {
        $this->modelivraison = $modelivraison;
    
        return $this;
    }

    /**
     * Get modelivraison
     *
     * @return string 
     */
    public function getModelivraison()
    {
        return $this->modelivraison;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return tempuser
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return tempuser
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return tempuser
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    
        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return tempuser
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set cp
     *
     * @param string $cp
     * @return tempuser
     */
    public function setCp($cp)
    {
        $this->cp = $cp;
    
        return $this;
    }

    /**
     * Get cp
     *
     * @return string 
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return tempuser
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    
        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set typemachine
     *
     * @param string $typemachine
     * @return tempuser
     */
    public function setTypemachine($typemachine)
    {
        $this->typemachine = $typemachine;
    
        return $this;
    }

    /**
     * Get typemachine
     *
     * @return string 
     */
    public function getTypemachine()
    {
        return $this->typemachine;
    }

    /**
     * Set marque
     *
     * @param \stdClass $marque
     * @return tempuser
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;
    
        return $this;
    }

    /**
     * Get marque
     *
     * @return \stdClass 
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set numserie
     *
     * @param string $numserie
     * @return tempuser
     */
    public function setNumserie($numserie)
    {
        $this->numserie = $numserie;
    
        return $this;
    }

    /**
     * Get numserie
     *
     * @return string 
     */
    public function getNumserie()
    {
        return $this->numserie;
    }

    /**
     * Set dateachat
     *
     * @param \DateTime $dateachat
     * @return tempuser
     */
    public function setDateachat($dateachat)
    {
        $this->dateachat = $dateachat;
    
        return $this;
    }

    /**
     * Get dateachat
     *
     * @return \DateTime 
     */
    public function getDateachat()
    {
        return $this->dateachat;
    }

    /**
     * Add versions
     *
     * @param \AcmeGroup\LaboBundle\Entity\version $versions
     * @return tempuser
     */
    public function addVersion(\AcmeGroup\LaboBundle\Entity\version $versions)
    {
        $this->versions[] = $versions;
    
        return $this;
    }

    /**
     * Remove versions
     *
     * @param \AcmeGroup\LaboBundle\Entity\version $versions
     */
    public function removeVersion(\AcmeGroup\LaboBundle\Entity\version $versions)
    {
        $this->versions->removeElement($versions);
    }

    /**
     * Get versions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVersions()
    {
        return $this->versions;
    }



}
