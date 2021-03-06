<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\laboBaseRepository;

/**
 * pagewebRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class pagewebRepository extends laboBaseRepository {

	public function findWithRichtexts($slug) {
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.slug = :slug')
			->setParameter('slug', $slug)
			->leftJoin('element.richtexts', 'r')
			;
		$r = $qb->getQuery()->getOneOrNullResult();
		return $r;
	}

}
