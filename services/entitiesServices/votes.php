<?php
// src/AcmeGroup/services/entitiesServices/votes.php

namespace AcmeGroup\services\entitiesServices;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AcmeGroup\services\entitiesServices\entitiesGeneric;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
// use Symfony\Component\Form\FormFactoryInterface;

class votes extends entitiesGeneric {
	protected $service = array();

	public function __construct(ContainerInterface $container) {
		parent::__construct($container);
		$this->defineEntity("voteArticle");
	}

	/**
	 * voteArticle
	 * @param \AcmeGroup\LaboBundle\Entity\article $article
	 * @param string $note
	 * @param string $avis
	 * @param \AcmeGroup\userBundle\Entity\User $user
	 * @return array
	 */
	public function voteForArticle($article, $note, $avis, $user) {
		if($this->dejaVote($article, $user) === null) {
			// crée un nouvel objet voteArticle
			$newVote = $this->newObject();
			$newVote->setUser($user);
			$newVote->setArticle($article);
			$newVote->setNote($note);
			$newVote->setAvis($avis);
			$this->em->persist($newVote);
			$this->em->flush();
			// changement de note de l'article (recalcul complet)
			$article->setNotation($this->calculeNoteArticle($article));
			$this->em->persist($article);
			$this->em->flush();

			// veleurs de retour
			$vote['message'] = "Votre vote a été enregistré !<br />Merci et bonne continuation sur SingerFrance.com";
			$vote["result"] = true;
		} else {
			// déjà voté !!!
			$vote["result"] = false;
			$vote["nbvotes"] = $this->dejaVote($article, $user);
			$vote['message'] = "Vous avez déjà voté pour cet article.";
		}
		return $vote;
	}

	/**
	 * dejaVote
	 * @param \AcmeGroup\LaboBundle\Entity\article $article / ou id article
	 * @param \AcmeGroup\userBundle\Entity\User $user / ou id user
	 * @return objet ou null
	 */
	public function dejaVote($article, $user) {
		if(is_object($article)) $article = $article->getId();
		if(is_object($user)) $user = $user->getId();
		$r = $this->getRepo()->findVote($article, $user);
		if(count($r) > 0) return $r;
			else return null;
	}

	public function calculeNoteArticle($article) {
		$allvotes = $this->findAllVotesForArticle($article);
		$nbvotes = count($allvotes);
		$cumul = 0;
		foreach($allvotes as $vote) $cumul += $vote->getNote();
		return round($cumul / $nbvotes);
	}

	public function findAllVotesForArticle($article) {
		return $this->getRepo()->findAllVotes($article);
	}

	/**
	 * getCalcNoteArticle
	 * Renvoie la note globale de l'article $article
	 * @param objet article ou id article
	 * @return integer
	 */
	public function getCalcNoteArticle($article) {
		return $this->getRepo()->getCalcNoteArticle($article);
	}

}

?>
