<?php

namespace AcmeGroup\LaboBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * aelog
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AcmeGroup\LaboBundle\Entity\aelogRepository")
 */
class aelog {
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false, unique=false)
     */
    private $nom;

    /**
     * @var text
     *
     * @ORM\Column(name="url", type="text", nullable=true, unique=false)
     */
    private $url;

    /**
     * @var text
     *
     * @ORM\Column(name="parameter", type="text", nullable=true, unique=false)
     */
    private $parameter;

    /**
     * @var text
     *
     * @ORM\Column(name="controller", type="text", nullable=true, unique=false)
     */
    private $controller;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", nullable=true, unique=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=24, nullable=true, unique=false)
     */
    private $ip;

    /**
     * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true, unique=false)
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="versionslug", type="string", length=64, nullable=true, unique=false)
     */
    private $versionSlug;


    public function __construct() {
        $this->dateCreation = new \Datetime();
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
     * Set nom
     *
     * @param string $nom
     * @return aelog
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
     * Set url
     *
     * @param string $url
     * @return aelog
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set parameter
     *
     * @param string $parameter
     * @return aelog
     */
    public function setParameter($parameter)
    {
        $this->parameter = serialize($parameter);
    
        return $this;
    }

    /**
     * Get parameter
     *
     * @return string 
     */
    public function getParameter()
    {
        return unserialize($this->parameter);
    }

    /**
     * Set controller
     *
     * @param string $controller
     * @return aelog
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    
        return $this;
    }

    /**
     * Get controller
     *
     * @return string 
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set code
     *
     * @param integer $code
     * @return aelog
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return aelog
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set user
     *
     * @param $user
     * @return aelog
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return aelog
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    
        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return aelog
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    
        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set versionSlug
     *
     * @param $versionSlug
     * @return aelog
     */
    public function setVersionSlug($versionSlug)
    {
        $this->versionSlug = $versionSlug;
    
        return $this;
    }

    /**
     * Get versionSlug
     *
     * @return integer 
     */
    public function getVersionSlug()
    {
        return $this->versionSlug;
    }




}
