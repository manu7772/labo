<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\laboBaseRepository;

/**
 * marqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class marqueRepository extends laboBaseRepository {

	/** Renvoie la(les) valeur(s) par défaut --> ATTENTION : dans un array()
	* @param $defaults = liste des éléments par défaut
	*/
	public function defaultVal($defaults = null) {
		if($defaults === null) $defaults = array("Singer");
		$qb = $this->createQueryBuilder('s');
		$qb->where($qb->expr()->in('s.nom', $defaults));
		return $qb->getQuery()->getResult();
	}

}
