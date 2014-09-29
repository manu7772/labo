<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use labo\Bundle\TestmanuBundle\Entity\laboBaseRepository;

/**
 * imageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class imageRepository extends laboBaseRepository {
	

	/**
	 * findAllFileNames
	 *
	 * @return array liste des noms des fichiers
	 */
	public function findAllFileNames() {
		$qb = $this->createQueryBuilder('element');
		$r = $qb->getQuery()->getResult();
		$ret = array();
		foreach($r as $ent) $ret[] = $ent->getFichierNom();
		return $ret;
	}

	/**
	* findImageByTypes
	* Sélect les images de types $types
	*
	*/
	public function findImageByTypes($types) { // $types = array des types à lister
		$qb = $this->createQueryBuilder('element');
		$qb = $this->imageByTypes($qb, $types);
		return $qb;
	}



	/***************************************************************/
	/*** Méthodes conditionnelles
	/***************************************************************/

	/**
	* imageByTypes
	* Sélect les images de types $types
	*
	*/
	public function imageByTypes(\Doctrine\ORM\QueryBuilder $qb, $types) { // $types = array des types à lister
		$qb->join('element.typeImages', 't')
			->where($qb->expr()->in('t.nom', $types));
		return $qb;
	}



}
