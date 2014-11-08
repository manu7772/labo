<?php
// labo/Bundle/TestmanuBundle/services/entitiesServices/panier.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\entitiesServices\entitiesGeneric;
// use labo\Bundle\TestmanuBundle\Entity\article;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class panier extends entitiesGeneric {
	protected $service = array();
	protected $security_context;
	protected $user;

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->defineEntity("panier");
		// $this->version = $this->container->get('acmeGroup.version')->getActualVersionObj();
		$this->loadCurrentUser(); // chargement de l'utilisateur courant dans $this->user
		// $this->getRepo()->setUser($this->user); // charge l'utilisateur dans le repository en référence
	}

	// public function getUser() { // ???????????????????
	// 	return $this->user;
	// }

	/**
	 * messageNonConnecte
	 * @return string
	 */
	public function messageNonConnecte() {
		$login = '<a href="'.$this->router->generate('fos_user_security_login').'"><button type="button" class="btn btn-warning">LOGIN</button></a>';
		$regis = '<a href="'.$this->router->generate('fos_user_registration_register').'"><button type="button" class="btn btn-danger">Créer mon compte</button></a>';
		return "Vous devez vous connecter à votre compte pour acheter en ligne.<br />Si vous n'en avez pas, vous pouvez créer un compte facilement.<br /><br />".$login."&nbsp;".$regis;
	}

	/**
	 * ajouteArticle
	 * @param AcmeGroup\LaboBundle\Entity\article $article
	 * @param AcmeGroup\UserBundle\Entity\User $user
	 * @param integer $quantite
	 * @return aeReponse
	 */
	public function ajouteArticle(\AcmeGroup\LaboBundle\Entity\article $article, $user = null, $quantite = 1) {
		if($user === null) $user = $this->user;
		if(is_object($user)) {
			$art = $this->getRepo()->getOneArticleOfUser($article->getId(), $user->getId());
			if($art === null) {
				// article non présent dans le panier
				$art = $this->newObject();
				$art->setPropUser($user);
				$art->setArticle($article);
				$art->setQuantite($quantite);
				$this->getEm()->persist($art);
				$r = new aeReponse(true, null, "L'article ".$article->getNom()." a été ajouté au panier.");
			} else {
				// article déjà présent au moins 1 fois
				$art->ajouteQuantite($quantite);
				$r = new aeReponse(true, null, null);
			}
			$this->getEm()->flush();
			return $r;
			// return new aeReponse(false, null, "L'article n'a pu être ajouté.");
		} else {
			// generate
			return new aeReponse(false, null, $this->messageNonConnecte());
		}
	}

	/**
	 * reduitArticle
	 * @param AcmeGroup\LaboBundle\Entity\article $article
	 * @param AcmeGroup\UserBundle\Entity\User $user
	 * @param integer $quantite
	 * @return aeReponse
	 */
	public function reduitArticle(\AcmeGroup\LaboBundle\Entity\article $article, $user = null, $quantite = 1) {
		if($user === null) $user = $this->user;
		if(is_object($user)) {
			$art = $this->getRepo()->getOneArticleOfUser($article->getId(), $user->getId());
			if($art === null) {
				// article non présent dans le panier
				return new aeReponse(false, null, "L'article ".$article->getNom()." n'existe pas dans le panier.");
			} else {
				// article déjà présent au moins 1 fois
				$art->retireQuantite($quantite);
				if($art->getQuantite() < 1) {
					// plus d'articles…
					$this->getEm()->remove($art);
					$r = new aeReponse(true, null, "L'article ".$article->getNom()." a été supprimé du panier.");
				} else {
					// reste encore un ou des articles…
					$r = new aeReponse(true, null, null);
				}
			}
			$this->getEm()->flush();
			return $r;
			// return new aeReponse(false, null, "L'article n'a pu être ajouté.");
		} else {
			// generate
			return new aeReponse(false, null, $this->messageNonConnecte());
		}
	}

	/**
	 * SupprimeArticle
	 * @param AcmeGroup\LaboBundle\Entity\article $article
	 * @param AcmeGroup\UserBundle\Entity\User $user
	 * @return aeReponse
	 */
	public function SupprimeArticle(\AcmeGroup\LaboBundle\Entity\article $article, $user = null) {
		if($user === null) $user = $this->user;
		if(is_object($user)) {
			$art = $this->getRepo()->getOneArticleOfUser($article->getId(), $user->getId());
			if($art === null) {
				// article non présent dans le panier
				return new aeReponse(false, null, "L'article ".$article->getNom()." n'existe pas dans le panier.");
			} else {
				// article présent
				$this->getEm()->remove($art);
				$this->getEm()->flush();
			}
			return new aeReponse(true, null, "L'article ".$article->getNom()." a été supprimé du panier.");
			// return new aeReponse(false, null, "L'article n'a pu être ajouté.");
		} else {
			// generate
			return new aeReponse(false, null, $this->messageNonConnecte());
		}
	}

	/**
	 * videPanier
	 * @param AcmeGroup\UserBundle\Entity\User $user
	 * @return aeReponse
	 */
	public function videPanier($user = null) {
		if($user === null) $user = $this->user;
		if(is_object($user)) {
			$art = $this->getRepo()->getUserArticles($user->getId());
			if(count($art) < 1) {
				// Le panier est déjà vide
				$r = new aeReponse(false, null, "Le panier est déjà vide.");
			} else {
				// Le panier contient au moins 1 article
				foreach($art as $artsupp) $this->getEm()->remove($artsupp);
				$this->getEm()->flush();
				$r = new aeReponse(true, null, "Le panier a été vidé.");
			}
			return $r;
			// return new aeReponse(false, null, "L'article n'a pu être ajouté.");
		} else {
			// generate
			return new aeReponse(false, null, $this->messageNonConnecte());
		}
	}

	/**
	 * getArticlesOfUser
	 * @param AcmeGroup\UserBundle\Entity\User $user
	 * @return array of article
	 */
	public function getArticlesOfUser($user = null) {
		if($user === null) $user = $this->user;
		if(is_object($user)) {
			// if($user === null) $user = $this->user;
			return $this->getRepo()->getUserArticles($user->getId());
		} else return null;
	}

	/**
	 * getInfosPanier
	 * Renvoie le nombre d'articles dans un array :
	 * -> "bytype" = nombre d'articles différents
	 * -> "total" = nombre total d'articles
	 * @param AcmeGroup\UserBundle\Entity\User $user
	 * @return array
	 */
	public function getInfosPanier($user = null) {
		if($user === null) $user = $this->user;
		if(is_object($user)) {
			// if($user === null) $user = $this->user;
			$articles = $this->getRepo()->getUserPanier($user->getId());
			$infopanier["total"] = 0;
			$infopanier["prixtotal"] = 0;
			foreach($articles as $art) {
				$infopanier["total"] += $art->getQuantite();
				$infopanier["prixtotal"] += $art->getPrixtotal();
			}
			$infopanier["bytype"] = count($articles);
			return $infopanier;
		} else return null;
	}


}


?>
