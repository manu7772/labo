<?php

namespace AcmeGroup\LaboBundle\Entity;

use AcmeGroup\LaboBundle\Entity\baseRepository;

/**
 * aelogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class aelogRepository extends baseRepository {

	/**
	 * findByRoute
	 * Renvoie les statistiques par route.
	 * @param string $route - nom de la route
	 * @return array
	 */
	public function findByRoute($route = null) {
		if($route === null) $route = 'acme_site_home';
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.nom = :nom')
			->setParameter('nom', $route)
			->andWhere('element.dateCreation >= :auj')
			->setParameter('auj', new \Datetime(date("Y-m-d 00:00:00")))
			// ajout utilisateur
			->leftJoin('element.user', 'u')->addSelect('u');
		$qb = $this->withVersion($qb);
		$qb->orderBy('element.dateCreation', 'DESC');
		$r = $qb->getQuery()->getResult();
		return $r;
	}

	/**
	 * findByIp
	 * Renvoie les statistiques par IP.
	 * @param $ip adresse ip
	 * @return array
	 */
	public function findByIp($ip, $dateDebut = null, $dateFin = null) {
		// contrôle dates et transformation en objet Datetime
		if(is_string($dateDebut)) $dateDebut = new \Datetime(date($dateDebut));
		if(is_string($dateFin)) $dateFin = new \Datetime(date($dateFin));
		if($dateFin === null) $dateFin = new \Datetime();
		// Query
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.ip = :ip')
			->setParameter('ip', $ip);
		if($dateDebut !== null) {
			$qb->andWhere('element.dateCreation >= :date')
				->setParameter('date', $dateDebut);
			}
		$qb->andWhere('element.dateCreation <= :dateF')
			->setParameter('dateF', $dateFin);
		// ajout utilisateur
		$qb->leftJoin('element.user', 'u')->addSelect('u');
		$qb = $this->withVersion($qb);
		$qb->orderBy('element.dateCreation', 'DESC');
		$r = $qb->getQuery()->getResult();
		return $r;
	}

	/**
	 * findByIp
	 * Renvoie les statistiques par article.
	 * @param $id - id de l'article
	 * @return array
	 */
	public function findArticle($articleSlug, $dateDebut = null, $dateFin = null) {
		// contrôle dates et transformation en objet Datetime
		if(is_string($dateDebut)) $dateDebut = new \Datetime(date($dateDebut));
		if(is_string($dateFin)) $dateFin = new \Datetime(date($dateFin));
		if($dateFin === null) $dateFin = new \Datetime();
		// Query
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.url = :url')
			->setParameter('url', "/fiche-article/".$articleSlug);
		if($dateDebut !== null) {
			$qb->andWhere('element.dateCreation >= :date')
				->setParameter('date', $dateDebut);
			}
		$qb->andWhere('element.dateCreation <= :dateF')
			->setParameter('dateF', $dateFin);
		// ajout utilisateur
		// $qb->leftJoin('element.user', 'u')->addSelect('u');
		$qb = $this->withVersion($qb);
		$qb->orderBy('element.dateCreation', 'ASC');
		$r = $qb->getQuery()->getResult();
		return $r;
	}



}
