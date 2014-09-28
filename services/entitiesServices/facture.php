<?php
// src/AcmeGroup/services/entitiesServices/facture.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class facture extends entitiesGeneric {
	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->defineEntity("facture");
	}

	/**
	 * createNewByPanier
	 * Enregistre une nouvelle facture d'après utilisateur et paramètres
	 *
	 * @param user $user
	 * @param version $version
	 * @param array $data
	 * @return facture
	 */
	public function createNewByPanier($user, $version, $panier, $data, $statut) {
		//
		$fact = $this->getRepo()->findByReference($data["reference"]);
		if(count($fact) < 1) {
			$infos = array(
				'amount', 'merchant_id', 'transaction_id', 'currency_code', 'payment_means',
				'transmission_date', 'payment_time', 'payment_date', 'card_number',
				'response_code', 'bank_response_code', 'reference'
				);
			$facture = $this->newObject();
			
			$facture->setPropUser($user);
			$facture->addVersion($version);
			$facture->setStatut($statut);
			// Adresse pour cette facture (car elle pourrait changer)
			$facture->setNom($user->getNom());
			$facture->setPrenom($user->getPrenom());
			$facture->setAdresse($user->getAdresse());
			$facture->setCp($user->getCp());
			$facture->setVille($user->getVille());
			$facture->setCommentaire($user->getCommentaire());
			$facture->setTel($user->getTel());
			$facture->setEmail($user->getEmail());
			// mode de livraison
			$facture->setLivraison($user->getLivraison());

			foreach($infos as $lib) {
				$methode = "set".ucfirst(str_replace("_", "", $lib));
				if(method_exists($facture, $methode)) {
					if(isset($data[$lib])) {
						$facture->$methode($data[$lib]);
					} else {
						$facture->$methode("");
					}
				}
			}
			// vérification code de réponse de la banque (bank_response_code)
			if((intval($facture->getBankresponsecode()) > 0) || (intval($facture->getResponsecode()) > 0)) {
				$facture->setStade(100);
			}
			//
			$detailbyarticle = array();
			foreach($panier->getArticlesOfUser($user) as $article) {
				$art = $article->getArticle();
				// incrémente ventes article
				if(strtolower($statut->getNom()) == "actif") $art->addVentes($article->getQuantite());
				$this->getEm()->persist($art);
				// objets articles
				$facture->addArticle($art);
				// articles mémo : quantités / nom / prix / etc.
				$detailbyarticle[$art->getId()]["quantite"] = $article->getQuantite();
				$detailbyarticle[$art->getId()]["nom"] = $art->getNom();
				$detailbyarticle[$art->getId()]["prix"] = $art->getPrix();
				$detailbyarticle[$art->getId()]["TVA"] = $art->getTauxTVA()->getTaux();
			}
			$facture->setDetailbyarticle($detailbyarticle);
	
			$this->getEm()->persist($facture);
			$this->getEm()->flush();
		} else {
			$facture = $fact[0];
		}
		return $facture;
	}

	public function getFacturesOfUser($user, $tri = null, $sens = "DESC") {
		return $this->getRepo()->getFacturesOfUser($user, $tri, $sens);
	}

	/**
	 * getNbVentesArticle
	 * Retourne le nombre de ventes sur un article
	 * @param objet article ou id article
	 * @return integer
	 */
	public function getNbVentesArticle($article) {
		return $this->getRepo()->getNbVentesArticle($article);
	}

	/**
	 * getVentesErreur
	 * @return array
	 */
	public function getVentesErreur() {
		return $this->getRepo()->getVentesErreur();
	}

	/**
	 * getVentesCommande
	 * @return array
	 */
	public function getVentesCommande() {
		return $this->getRepo()->getVentesCommande();
	}

	/**
	 * getVentesLivraison
	 * @return array
	 */
	public function getVentesLivraison() {
		return $this->getRepo()->getVentesLivraison();
	}

	/**
	 * getVentesTermine
	 * @return array
	 */
	public function getVentesTermine() {
		return $this->getRepo()->getVentesTermine();
	}

	/**
	 * getVentesAnnule
	 * @return array
	 */
	public function getVentesAnnule() {
		return $this->getRepo()->getVentesAnnule();
	}

	/**
	 * check
	 * @return aeReponse
	 */
	public function check() {
		$r = array();
		$test = array("nom", "prenom", "adresse", "cp", "ville", "tel", "email");
		$factures = $this->getRepo()->findAll();
		foreach($factures as $facture) {
			$user = $facture->getPropUser();
			// vérification des coordonnées
			foreach($test as $tst) {
				$get = "get".ucfirst($tst);
				$set = "set".ucfirst($tst);
				if($facture->$get() == null || trim($facture->$get()) == "") {
					$facture->$set($user->$get());
					$r[$facture->getId()][$tst]["before"] = "";
					$r[$facture->getId()][$tst]["after"] = $user->$get();
				}
			}
			// Erreur retour de banque
			if((intval($facture->getBankresponsecode()) > 0)) {
				if($facture->getStade() < 100) {
					$r[$facture->getId()]["bankresponsecode"]["before"] = $facture->getStadeTxt();
					$facture->setStade(100);
					$r[$facture->getId()]["bankresponsecode"]["after"] = $facture->getStadeTxt();
				}
			} else if($facture->getStade() > 99) {
				// si erreur de manipulation : réinitialisation
				$r[$facture->getId()]["bankresponsecode"]["before"] = $facture->getStadeTxt();
				$facture->setStade(0);
				$r[$facture->getId()]["bankresponsecode"]["after"] = $facture->getStadeTxt();
			}
		}
		$this->getEm()->flush();
		return new aeReponse(1, $r, "Check commandes/factures terminé");
	}

}

?>
