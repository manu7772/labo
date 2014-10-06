<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\laboBaseRepository;

/**
 * statutRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class partenaireRepository extends laboBaseRepository {

	/**
	 *
	 *
	 */
	public function findAllWithFlux() {
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.urlflux != :uf')
			->setParameter('uf', 'NULL');
		$qb = $this->withVersion($qb);
		$qb = $this->defaultStatut($qb);
		return $qb->getQuery()->getResult();
	}

}
