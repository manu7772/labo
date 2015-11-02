<?php
// labo/Bundle/TestmanuBundle/services/entitiesServices/article.php

namespace labo\Bundle\TestmanuBundle\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use labo\Bundle\TestmanuBundle\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use labo\Bundle\TestmanuBundle\services\aetools\aeReponse;
// use Symfony\Component\Form\FormFactoryInterface;

class article extends entitiesGeneric {
	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->defineEntity("article");
	}

	/**
	 * getArticleById
	 * @param string $id
	 * @return article
	 */
	public function getArticleById($id) {
		$article = $this->getRepo()->find($id);
		if(is_object($article)) return $article;
			else return false;
	}

	/**
	 * getArticlesByCategorie
	 * @param string $categorieSlug
	 * @return array $data["articles"][nomCategorie][nomArticle] = objet article
	 */
	public function getArticlesByCategorie($categorieSlug) {
		$data["categorieSlug"] = $categorieSlug;
		$data["articles"] = $this->getRepo()->findArtByCategorie($categorieSlug);
		return $data;
	}

	/**
	 * getArticlesEBoutique
	 * @return array $data["articles"][nomCategorie][nomArticle] = objet article
	 */
	public function getArticlesByReseau($reseauNom) {
		$Tidx = $this->container->get("session")->get('version');
		$data = $this->getRepo()->setVersion($Tidx["nom"])->findArtByReseau($reseauNom);
		return $data;
	}

	/**
	 * getInfoFiche
	 * @param integer $id
	 * @return \AcmeGroup\LaboBundle\Entity\article
	 */
	public function getInfoFiche($id) {
		$Tidx = $this->container->get("session")->get('version');
		$r = $this->getRepo()->setVersion($Tidx["nom"])->findArticleFicheWithUser($id);
		if(count($r) < 1) $rep = null; else $rep = $r[0];
		return $rep;
		// return $this->getRepo()->findForFiche($id, $user);
	}

	/**
	 * check
	 * @return aeReponse
	 */
	public function check() {
		$r = array();
		$articles = $this->getRepo()->findAll();
		foreach($articles as $article) {
			// calcul des prix (TTC/HT/etc.)
			$article->setPrix($article->getPrix()); // ---> recalcule prix HT automatiquement
			// ventes
			$this->defineEntity("facture");
			$nb = $this->getRepo()->getNbVentesArticle($article);
			$before = $article->getVentes();
			if($before != $nb) {
				$r[$article->getId()]["ventes"]["format"] = "integer";
				$r[$article->getId()]["ventes"]["before"] = $before;
				$r[$article->getId()]["ventes"]["after"] = $nb;
				$article->setVentes($nb);
			}
			$this->restitutePreviousEntity();
			// notation
			$this->defineEntity("voteArticle");
			$nb = $this->getRepo()->getCalcNoteArticle($article);
			$before = $article->getNotation();
			if($before != $nb) {
				$r[$article->getId()]["notation"]["format"] = "integer";
				$r[$article->getId()]["notation"]["before"] = $before;
				$r[$article->getId()]["notation"]["after"] = $nb;
				$article->setNotation($nb);
			}
			$this->restitutePreviousEntity();
		}
		// enregistrement
		// $this->defineEntity("article");
		$this->getEm()->flush();
		return new aeReponse(1, $r, "Check articles terminé");
	}

}