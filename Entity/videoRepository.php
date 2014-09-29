<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\laboBaseRepository;

/**
 * videoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class videoRepository extends laboBaseRepository {

	public function getVideos($recherche = null) {
		$qb = $this->createQueryBuilder('element');
		$qb = $this->excludeExpired($qb);
		$qb = $this->defaultStatut($qb);
		$qb = $this->withVersion($qb);
		$qb->orderBy('element.dateCreation', 'DESC');
		return $qb->getQuery()->getResult();
	}


}