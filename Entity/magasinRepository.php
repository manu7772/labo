<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\laboBaseRepository;

/**
 * magasinRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class magasinRepository extends laboBaseRepository {


	/**
	 * findByDept
	 *
	 * @param string
	 * @return array
	 */
	public function findByDept($searchString) {
		$qb = $this->createQueryBuilder("element");
		$qb = $this->rechercheStr($qb, $searchString, "cp", 'begin');
		$qb->orderBy('element.plusVisible', 'DESC')
			->addOrderBy('element.ville', 'ASC');
		$qb = $this->defaultStatut($qb);
		return $qb->getQuery()->getResult();
	}

	/**
	 * findByVille
	 *
	 * @param string
	 * @return array
	 */
	public function findByVille($searchString = "", $onlyQB = false) {
		$qb = $this->createQueryBuilder("element");
		// si $searchString est vide, renvoie la liste complète…
		if($searchString !== "" || $searchString !== null) {
			$qb = $this->rechercheStr($qb, $searchString, "ville");
		}
		$qb->orderBy('element.plusVisible', 'DESC')
			->addOrderBy('element.ville', 'ASC');
		$qb = $this->defaultStatut($qb);
		if($onlyQB === false) return $qb->getQuery()->getResult();
			else return $qb;
	}

	/**
	 * findListeVilles
	 * 
	 * @return array
	 */
	public function findListeVilles() {
		$qb = $this->createQueryBuilder("element");
		$qb
			// ->orderBy('element.plusVisible', 'DESC')
			->addOrderBy('element.ville', 'ASC');
		$qb = $this->defaultStatut($qb);
		$a = $qb->getQuery()->getResult();
		$liste = array();
		foreach($a as $n => $magasin) {
			$ville = $magasin->getVille();
			if(!in_array($ville, $liste) && $ville != "") $liste[] = $ville;
		}
		return $liste;
	}

	/**
	 * find for menu
	 */
	public function findNomformenu() {
		$qb = $this->createQueryBuilder("element");
		$qb->orderBy('element.cp', 'ASC');
		$qb = $this->genericFilter($qb);
		return $qb;
		// return $qb->getQuery()->getResult();
	}

	public function findMagSansMail() {
		$qb = $this->createQueryBuilder("element");
		$qb->where('element.email = :mail1')
			->setParameter('mail1', null)
			->orWhere('element.email = :mail2')
			->setParameter('mail2', "")
			;
		return $qb->getQuery()->getResult();
	}

}
