<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\laboBaseRepository;

/**
 * statutRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class statutRepository extends laboBaseRepository {

	/** Renvoie la(les) valeur(s) par défaut --> ATTENTION : dans un array()
	* @param $defaults = liste des éléments par défaut
	*/
	public function defaultVal($defaults = null) {
		if($defaults === null) $defaults = array("Actif");
		$qb = $this->createQueryBuilder('element');
		$qb->where($qb->expr()->in('element.nom', $defaults));
		return $qb->getQuery()->getResult();
	}

	public function defaultValClosure($role = "ROLE_USER") {
		$list = null;
		switch($role) {
			case "ROLE_EDITOR":
				$list = array("Actif", "Inactif");
				break;
			case "ROLE_USER":
				$list = array("Actif", "Inactif");
				break;
			case "ROLE_ADMIN":
				$list = array("Actif", "Inactif");
				break;
			case "ROLE_SUPER_ADMIN":
				// all
				break;
			default:
				$list = array("Actif", "Inactif");
				break;
		}
		$qb = $this->createQueryBuilder('element');
		if(is_array($list)) $qb->where($qb->expr()->in('element.nom', $list));
		return $qb;
	}

}