<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\laboBaseRepository;

/**
 * ficheCreativeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ficheCreativeRepository extends laboBaseRepository {

	/**
	* findActifs
	* Liste des éléments de statut = actif
	* @return array
	*/
	public function findAllFiches() {
		$qb = $this->createQueryBuilder('element');
		$qb->leftJoin('element.image', 'i')
			->addSelect('i')
			;
		$qb = $this->genericFilter($qb);
		$qb->orderBy('element.dateCreation', 'DESC');
		return $qb->getQuery()->getResult();
	}

	/**
	* getFicheBySlug
	* Renvoie une fiche selon slug
	* @return array
	*/
	public function getFicheBySlug($slug) {
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.slug = :slug')
			->setParameter('slug', $slug)
			->leftJoin('element.image', 'i')
			->addSelect('i')
			;
		$qb = $this->genericFilter($qb);
		// $qb->orderBy('element.dateCreation', 'DESC');
		return $qb->getQuery()->getResult();
	}


}
