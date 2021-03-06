<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\laboBaseRepository;

/**
 * versionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class versionRepository extends laboBaseRepository {

	/**
	* defaultVal
	* Renvoie les versions pour fixtures
	*/
	public function defaultVal() {
		$qb = $this->createQueryBuilder('element');
		// $qb->where('element.defaut = :true')
		// 	->setParameter('true', 1);
		return $qb->getQuery()->getResult();
	}

	/**
	* defaultVersion
	* Renvoie l'instance de la version par défaut (ou null)
	*/
	public function defaultVersion() {
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.defaut = :true')
			->setParameter('true', 1);
		return $qb->getQuery()->getOneOrNullResult();
	}

}

