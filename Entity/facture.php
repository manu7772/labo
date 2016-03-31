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
abstract class facture {

	protected $list_stades = array(
		"commande" 		=> 0,
		"livraison" 	=> 1,
		"termine" 		=> 2,
		"annule"		=> 3,
		"erreur"		=> 100,
		);

	protected $modeslivraison;

	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="reference", type="string", length=128, unique=true, nullable=false)
	 */
	protected $reference;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dateCreation", type="datetime", nullable=false)
	 */
	protected $dateCreation;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="stade", type="smallint", nullable=false, unique=false)
	 */
	protected $stade;

	/**
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\article")
	 * @ORM\JoinColumn(nullable=false, unique=true)
	 */
	protected $articles;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\version")
	 */
	protected $versions;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\statut")
	 * @ORM\JoinColumn(nullable=false, unique=false)
	 */
	protected $statut;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="detailbyarticle", type="text", nullable=true, unique=false)
	 */
	protected $detailbyarticle;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="amount", type="string", length=16, nullable=true, unique=false)
	 */
	protected $amount;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="merchantid", type="string", length=24, nullable=true, unique=false)
	 */
	protected $merchantid;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="transactionid", type="string", length=24, nullable=true, unique=false)
	 */
	protected $transactionid;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="currencycode", type="string", length=8, nullable=true, unique=false)
	 */
	protected $currencycode;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="paymentmeans", type="string", length=16, nullable=true, unique=false)
	 */
	protected $paymentmeans;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="transmissiondate", type="string", length=16, nullable=true, unique=false)
	 */
	protected $transmissiondate;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="paymenttime", type="string", length=8, nullable=true, unique=false)
	 */
	protected $paymenttime;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="paymentdate", type="string", length=8, nullable=true, unique=false)
	 */
	protected $paymentdate;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="cardnumber", type="string", length=16, nullable=true, unique=false)
	 */
	protected $cardnumber;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="responsecode", type="string", length=8, nullable=true, unique=false)
	 */
	protected $responsecode;

   	/**
	 * @var string
	 *
	 * @ORM\Column(name="bankresponsecode", type="string", length=8, nullable=true, unique=false)
	 */
	protected $bankresponsecode;

	/**
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\UserBundle\Entity\User")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	protected $propUser;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="livraison", type="string", length=100, nullable=true, unique=false)
	 */
	protected $livraison;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=100, nullable=true, unique=false)
	 */
	protected $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="prenom", type="string", length=100, nullable=true, unique=false)
	 */
	protected $prenom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="tel", type="string", length=15, nullable=true, unique=false)
	 */
	protected $tel;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=100, nullable=true, unique=false)
	 */
	protected $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="adresse", type="text", nullable=true, unique=false)
	 */
	protected $adresse;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cp", type="string", length=10, nullable=true, unique=false)
	 */
	protected $cp;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ville", type="string", length=255, nullable=true, unique=false)
	 */
	protected $ville;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="commentaire", type="text", nullable=true, unique=false)
	 */
	protected $commentaire;

	protected $venteValid;
	protected $validResult;


	public function __construct() {
		$this->dateCreation = new \Datetime();
		$this->articles = new ArrayCollection();
		$this->versions = new ArrayCollection();
		$this->stade = $this->list_stades["commande"];

		// récupère les paramètres livraison depuis User
		$user = new \AcmeGroup\UserBundle\Entity\User();
		$this->modeslivraison = $user->getModeslivraison();
		// $this->modeslivraison = array(
		// "poste"		=> "Par voie postale",
		// "depot"		=> "Enlèvement en boutique",
		// );
		$this->livraison = $user->getLivraison();
		$this->venteValid = null;
		$this->validResult = "00";
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
	 * Renvoie l'état de la vente
	 * @return boolean
	 */
	public function isValidVente() {
		$result = true;
		if($this->getResponsecode() !== $this->validResult) $result = false;
		if($this->getBankresponsecode() !== $this->validResult) $result = false;
		// résultat : boolean
		return $result;
	}

	/**
	 * Set reference
	 *
	 * @param string $reference
	 * @return facture
	 */
	public function setReference($reference) {
		// on ne doit pas pouvoir changer la référence une fois établie !!!
		if($this->reference === null) $this->reference = $reference;
	
		return $this;
	}

	/**
	 * Get reference
	 *
	 * @return string 
	 */
	public function getReference() {
		return $this->reference;
	}

	/**
	 * Set stade
	 *
	 * @param integer/string $stade
	 * @return facture
	 */
	public function setStade($stade) {
		if(array_key_exists($stade, $this->list_stades)) {
			// par nom
			$this->stade = $this->list_stades[$stade];
		} else if(in_array(intval($stade), $this->list_stades)) {
			// par chiffre
			$this->stade = intval($stade);
		}
	
		return $this;
	}

	/**
	 * Get stade
	 *
	 * @return integer 
	 */
	public function getStade() {
		return $this->stade;
	}

	/**
	 * Get stadeTxt
	 *
	 * @return string 
	 */
	public function getStadeTxt() {
		foreach($this->list_stades as $k => $v)
			if($v == $this->stade) return $k;
	}

	/**
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 * @return facture
	 */
	public function setDateCreation($dateCreation) {
		// la date de création ne doit pas être changée !!!
		// $this->dateCreation = $dateCreation;
	
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
	 * Add articles
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $articles
	 * @return facture
	 */
	public function addArticle(\AcmeGroup\LaboBundle\Entity\article $articles) {
		$this->articles[] = $articles;
	
		return $this;
	}

	/**
	 * Remove articles
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\article $articles
	 */
	public function removeArticle(\AcmeGroup\LaboBundle\Entity\article $articles) {
		$this->articles->removeElement($articles);
	}

	/**
	 * Get articles
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getArticles() {
		return $this->articles;
	}

	/**
	 * Add versions
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\version $versions
	 * @return facture
	 */
	public function addVersion(\AcmeGroup\LaboBundle\Entity\version $versions) {
		$this->versions[] = $versions;
	
		return $this;
	}

	/**
	 * Remove versions
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\version $versions
	 */
	public function removeVersion(\AcmeGroup\LaboBundle\Entity\version $versions) {
		$this->versions->removeElement($versions);
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
	 * Set statut
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\statut $statut
	 * @return facture
	 */
	public function setStatut(\AcmeGroup\LaboBundle\Entity\statut $statut) {
		$this->statut = $statut;
	
		return $this;
	}

	/**
	 * Get statut
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\statut 
	 */
	public function getStatut() {
		return $this->statut;
	}

	/**
	 * Set detailbyarticle
	 *
	 * @param string $detailbyarticle
	 * @return facture
	 */
	public function setDetailbyarticle($detailbyarticle = "") {
		$this->detailbyarticle = serialize($detailbyarticle);
		return $this;
	}

	/**
	 * Get detailbyarticle
	 *
	 * @return string 
	 */
	public function getDetailbyarticle() {
		$detail = unserialize($this->detailbyarticle);
		// reconstitution des données calculées
		foreach($detail as $nom => $art) {
			// prix HT d'un article
			$detail[$nom]["prixUHt"] = floatval($art["prix"]) / (1 + ($art["TVA"] / 100));
			// prix HT de la quantité d'un article
			$detail[$nom]["prixTHt"] = intval($art["quantite"]) * floatval($art["prix"]) / (1 + ($art["TVA"] / 100));
			// prix TTC de la quantité d'un article
			$detail[$nom]["prixTTC"] = intval($art["quantite"]) * floatval($art["prix"]);
		}
		return $detail;
	}

	/**
	 * Get nbArticles
	 *
	 * @return string 
	 */
	public function getNbArticles() {
		$r = 0;
		$art = unserialize($this->detailbyarticle);
		foreach($art as $a) $r += $a["quantite"];
		return $r;
	}

	/**
	 * Set amount
	 *
	 * @param $amount
	 * @return facture
	 */
	public function setAmount($amount = "") {
		$this->amount = $amount."";
		return $this;
	}

	/**
	 * Get amount
	 *
	 * @return string 
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * Get prixtotal
	 *
	 * @return string 
	 */
	public function getPrixtotal() {
		if(intval($this->amount) > 0) {
			$montant = floatval($this->amount) / 100;
		} else {
			$montant = floatval(0);
			foreach($this->getDetailbyarticle() as $article) {
				$montant += intval($article["quantite"]) * floatval($article["prix"]);
			}
		}
		return $montant;
	}

	/**
	 * Get prixtotalHt
	 *
	 * @return string 
	 */
	public function getPrixtotalHt() {
		$montant = floatval(0);
		foreach($this->getDetailbyarticle() as $article) {
			$montant += intval($article["quantite"]) * floatval($article["prix"]) / (1 + ($article["TVA"] / 100));
		}
		return $montant;
	}

	/**
	 * Set merchantid
	 *
	 * @param string $merchantid
	 * @return facture
	 */
	public function setMerchantid($merchantid = "") {
		$this->merchantid = $merchantid;
		return $this;
	}

	/**
	 * Get merchantid
	 *
	 * @return string 
	 */
	public function getMerchantid() {
		return $this->merchantid;
	}

	/**
	 * Set transactionid
	 *
	 * @param string $transactionid
	 * @return facture
	 */
	public function setTransactionid($transactionid = "") {
		$this->transactionid = $transactionid;
		return $this;
	}

	/**
	 * Get transactionid
	 *
	 * @return string 
	 */
	public function getTransactionid() {
		return $this->transactionid;
	}

	/**
	 * Set currencycode
	 *
	 * @param string $currencycode
	 * @return facture
	 */
	public function setCurrencycode($currencycode = "") {
		$this->currencycode = $currencycode;
		return $this;
	}

	/**
	 * Get currencycode
	 *
	 * @return string 
	 */
	public function getCurrencycode() {
		return $this->currencycode;
	}

	/**
	 * Set paymentmeans
	 *
	 * @param string $paymentmeans
	 * @return facture
	 */
	public function setPaymentmeans($paymentmeans = "") {
		$this->paymentmeans = $paymentmeans;
		return $this;
	}

	/**
	 * Get paymentmeans
	 *
	 * @return string 
	 */
	public function getPaymentmeans() {
		return $this->paymentmeans;
	}

	/**
	 * Set transmissiondate
	 *
	 * @param string $transmissiondate
	 * @return facture
	 */
	public function setTransmissiondate($transmissiondate = "") {
		$this->transmissiondate = $transmissiondate;
		return $this;
	}

	/**
	 * Get transmissiondate
	 *
	 * @return string 
	 */
	public function getTransmissiondate() {
		return $this->transmissiondate;
	}

	/**
	 * Set paymenttime
	 *
	 * @param string $paymenttime
	 * @return facture
	 */
	public function setPaymenttime($paymenttime = "") {
		$this->paymenttime = $paymenttime;
		return $this;
	}

	/**
	 * Get paymenttime
	 *
	 * @return string 
	 */
	public function getPaymenttime() {
		return $this->paymenttime;
	}

	/**
	 * Set paymentdate
	 *
	 * @param string $paymentdate
	 * @return facture
	 */
	public function setPaymentdate($paymentdate = "") {
		$this->paymentdate = $paymentdate;
		return $this;
	}

	/**
	 * Get paymentdate
	 *
	 * @return string 
	 */
	public function getPaymentdatee() {
		return $this->paymentdate;
	}

	/**
	 * Set cardnumber
	 *
	 * @param string $cardnumber
	 * @return facture
	 */
	public function setCardnumber($cardnumber = "") {
		$this->cardnumber = $cardnumber;
		return $this;
	}

	/**
	 * Get cardnumber
	 *
	 * @return string 
	 */
	public function getCardnumber() {
		return $this->cardnumber;
	}

	/**
	 * Set responsecode
	 *
	 * @param string $responsecode
	 * @return facture
	 */
	public function setResponsecode($responsecode = "") {
		$this->responsecode = $responsecode;
		return $this;
	}

	/**
	 * Get responsecode
	 *
	 * @return string 
	 */
	public function getResponsecode() {
		return $this->responsecode;
	}

	/**
	 * Set bankresponsecode
	 *
	 * @param string $bankresponsecode
	 * @return facture
	 */
	public function setBankresponsecode($bankresponsecode = "") {
		$this->bankresponsecode = $bankresponsecode;
		return $this;
	}

	/**
	 * Get bankresponsecode
	 *
	 * @return string 
	 */
	public function getBankresponsecode() {
		return $this->bankresponsecode;
	}

	/**
	 * Set propUser
	 *
	 * @param \AcmeGroup\UserBundle\Entity\User $propUser
	 * @return facture
	 */
	public function setPropUser(\AcmeGroup\UserBundle\Entity\User $propUser = null) {
		$this->propUser = $propUser;
		return $this;
	}

	/**
	 * Get propUser
	 *
	 * @return \AcmeGroup\UserBundle\Entity\User 
	 */
	public function getPropUser() {
		return $this->propUser;
	}

	/**
	 * Set livraison
	 *
	 * @param string $livraison
	 * @return facture
	 */
	public function setLivraison($livraison) {
		$this->modeslivraison = array(
		"poste"		=> "Par voie postale",
		"depot"		=> "Enlèvement en boutique",
		);
		if((!array_key_exists($livraison, $this->modeslivraison)) || ($this->propUser->getMagasin() === null)) {
			$this->livraison = "poste";
		} else {
			$this->livraison = $livraison;
		}
		return $this;
	}

	/**
	 * Get livraison
	 *
	 * @return string 
	 */
	public function getLivraison() {
		// if($this->livraison === null) $this->setLivraison($this->livraison);
		return $this->livraison;
	}

	public function getLivraisonTxt() {
		$this->modeslivraison = array(
		"poste"		=> "Par voie postale",
		"depot"		=> "Enlèvement en boutique",
		);
		return $this->modeslivraison[$this->getLivraison()];
	}

	/**
	 * Set adresse
	 *
	 * @param string $adresse
	 * @return User
	 */
	public function setAdresse($adresse) {
		$this->adresse = $adresse;
	
		return $this;
	}

	/**
	 * Get adresse
	 *
	 * @return string 
	 */
	public function getAdresse() {
		return $this->adresse;
	}

	/**
	 * Set nom
	 *
	 * @param string $nom
	 * @return User
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
	 * Set prenom
	 *
	 * @param string $prenom
	 * @return User
	 */
	public function setPrenom($prenom) {
		$this->prenom = $prenom;
	
		return $this;
	}

	/**
	 * Get prenom
	 *
	 * @return string 
	 */
	public function getPrenom() {
		return $this->prenom;
	}

	/**
	 * Set tel
	 *
	 * @param string $tel
	 * @return User
	 */
	public function setTel($tel) {
		$this->tel = $tel;
	
		return $this;
	}

	/**
	 * Get tel
	 *
	 * @return string 
	 */
	public function getTel() {
		return $this->tel;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 * @return User
	 */
	public function setEmail($email) {
		$this->email = $email;
	
		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string 
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set cp
	 *
	 * @param string $cp
	 * @return User
	 */
	public function setCp($cp) {
		$this->cp = $cp;
	
		return $this;
	}

	/**
	 * Get cp
	 *
	 * @return string 
	 */
	public function getCp() {
		return $this->cp;
	}

	/**
	 * Set ville
	 *
	 * @param string $ville
	 * @return User
	 */
	public function setVille($ville) {
		$this->ville = $ville;
	
		return $this;
	}

	/**
	 * Get ville
	 *
	 * @return string 
	 */
	public function getVille() {
		return $this->ville;
	}

	/**
	 * Set commentaire
	 *
	 * @param string $commentaire
	 * @return User
	 */
	public function setCommentaire($commentaire) {
		$this->commentaire = $commentaire;
	
		return $this;
	}

	/**
	 * Get commentaire
	 *
	 * @return string 
	 */
	public function getCommentaire() {
		return $this->commentaire;
	}


}