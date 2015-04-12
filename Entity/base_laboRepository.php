<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

use Doctrine\ORM\QueryBuilder;

/**
 * base_laboRepository
 */
class base_laboRepository extends EntityRepository {

	const FIELD_DATE_EXPIRATION 	=	"dateExpiration";
	const FIELD_DATE_CREATION		=	"dateCreation";
	// Statut
	const FIELD_STATUT			=	"statut";
	const STATUT_ACTIF			=	"Actif";
	const STATUT_INACTIF		=	"Inactif";
	// Version
	const FIELD_VERSION			=	"version";

	protected $em;
	protected $version 			= null;
	protected $fields 			= null;
	protected $initCMD 			= false;
	protected $ClassMetadata;

	public function __construct(EntityManager $em, ClassMetadata $ClassMetadata) {
		$this->ClassMetadata = $ClassMetadata;
		$this->em = $em;
		parent::__construct($this->em, $this->ClassMetadata);
		$this->initCMData(true);
	}

	public function __call($name, $arguments = null) {
		switch ($name) {
			case 'is'.ucfirst($this->getName()):
				$reponse = true;
				break;
			default:
				$reponse = false;
				break;
		}
		return $reponse;
	}

	public function getName() {
		return 'base_laboRepository';
	}

	/**
	 * initCMData
	 */
	protected function initCMData($force = false) {
		if($this->initCMD === false || $force === true) {
			$this->fields = array();
			$this->initCMD = true;
			// ajout champs single
			$singles = $this->ClassMetadata->getColumnNames();
			foreach($singles as $nom) {
				$this->fields[$nom]['nom'] = $nom;
				$this->fields[$nom]['type'] = 'single';
			}
			// ajout champs associated
			$associations = $this->ClassMetadata->getAssociationMappings();
			foreach($associations as $nom => $field) {
				$this->fields[$nom]['nom'] = $nom;
				$this->fields[$nom]['type'] = 'association';
			}
		}
	}

	/**
	 * getFields
	 * Liste les champs de l'entité
	 */
	public function getFields() {
		if($this->fields === null) $this->initCMData(true);
		return $this->fields;
	}

	/**
	 * findActifs
	 * Liste de tous les éléments dont 
	 * - statut = actif
	 * - version = version actuelle
	 * - non expirés
	 * @return array
	 */
	public function findAllActifs() {
		$qb = $this->createQueryBuilder('element');
		$qb = $this->genericFilter($qb);
		// $qb->orderBy('element.id', 'DESC');
		return $qb->getQuery()->getResult();
	}

	/**
	 * Renvoie les entités dont le $champ 'single' contient les valeurs $values
	 * @param string $champ
	 * @param array $values
	 * @return array
	 */
	public function findByAttrib($champ, $values) {
		if(is_string($values)) $values = array($values);
		$qb = $this->createQueryBuilder('element');
		$qb->where($qb->expr()->in('element.'.$champ, $values));
		return $qb->getQuery()->getResult();
	}

	/**
	 * findXrandomElements
	 * récupère $n éléments au hasard dans la BDD
	 * @param integer $n
	 * @return array
	 */
	public function findXrandomElements($n, $onlyValids = true) {
		$n = intval($n);
		if($n < 1) $n = 1;
		$qb = $this->createQueryBuilder('element');
		if($onlyValids === true) $qb = $this->genericFilter($qb);
		$qb->setMaxResults($n);
		return $qb->getQuery()->getResult();
		// if($n > count($r)) $n = count($r);
		// shuffle($r);
		// $rs = array();
		// for ($i=0; $i < $n ; $i++) { 
		// 	$rs[] = $r[$i];
		// }
		return $r;
	}

	/**
	 * findElementsPagination
	 * Recherche les elements en fonction de la version
	 * et pagination avec GET
	 */
	// public function findElementsPagination($page = 1, $lignes = null, $ordre = 'id', $sens = 'ASC', $searchString = null, $searchField = "nom") {
	public function findElementsPagination($pag, $souscat = null) {
		// vérifications pagination
		if($pag['page'] < 1) $pag['page'] = 1;
		if($pag['lignes'] > 100) $pag['linges'] = 100;
		if($pag['lignes'] < 10) $pag['lignes'] = 10;
		// Requête…
		$qb = $this->createQueryBuilder('element');
		$qb = $this->rechercheStr($qb, $pag['searchString'], $pag['searchField']);
		// sous-catégories de tri
		if($souscat !== null) {
			$qb->join('element.'.$souscat['attrib'], 'link')
				->andWhere($qb->expr()->in('link.'.$souscat['column'], explode(":", $souscat['values'])));
		}
		// $qb->leftJoin('element.imagePpale', 'i')
		// 	->addSelect('i')
		// 	->leftJoin('element.images', 'ii')
		// 	->addSelect('ii')
		// 	->leftJoin('element.reseaus', 'r')
		// 	->addSelect('r');
		// exclusions
		// $qb = $this->excludeExpired($qb);
		$qb = $this->withVersion($qb);
		// $qb = $this->defaultStatut($qb);
		// Tri/ordre
		if(!in_array($pag['ordre'], $this->getFields())) $pag['ordre'] = "id";
		if(!in_array($pag['sens'], array('ASC', 'DESC'))) $pag['sens'] = "ASC";
		$qb->orderBy('element.'.$pag['ordre'], $pag['sens']);
		// Pagination
		$qb->setFirstResult(($pag['page'] - 1) * $pag['lignes'])
			->setMaxResults($pag['lignes']);
		return new Paginator($qb);
	}

	/**
	 * findImageByTypePagination
	 * Recherche les images en fonction de la version et du type
	 * tous types : 'all'
	 * et pagination avec GET
	 */
	// public function findImageByTypePagination($type, $page = 1, $lignes = null, $ordre = 'id', $sens = 'ASC', $searchString = null, $searchField = "nom") {
	// 	// vérifications pagination
	// 	if($page < 1) $page = 1;
	// 	if($lignes > 100) $lignes = 100;
	// 	if($lignes < 10) $lignes = 10;
	// 	// Requête…
	// 	$qb = $this->createQueryBuilder('element');
	// 	$qb = $this->rechercheStr($qb, $searchString, $searchField);
	// 	if($type !== 'all') {
	// 		$qb->join('element.typeImages', 'ti')
	// 			->where('ti.nom = :tinom')
	// 			->setParameter('tinom', $type);
	// 	}
	// 	// $qb->leftJoin('element.imagePpale', 'i')
	// 	// 	->addSelect('i')
	// 	// 	->leftJoin('element.images', 'ii')
	// 	// 	->addSelect('ii')
	// 	// 	->leftJoin('element.reseaus', 'r')
	// 	// 	->addSelect('r');
	// 	// exclusions
	// 	// $qb = $this->excludeExpired($qb);
	// 	$qb = $this->withVersion($qb);
	// 	// $qb = $this->defaultStatut($qb);
	// 	// Tri/ordre
	// 	if(!in_array($ordre, $this->getFields())) $ordre = "id";
	// 	if(!in_array($sens, array('ASC', 'DESC'))) $sens = "ASC";
	// 	$qb->orderBy('element.'.$ordre, $sens);
	// 	// Pagination
	// 	$qb->setFirstResult(($page - 1) * $lignes)
	// 		->setMaxResults($lignes);
	// 	return new Paginator($qb);
	// }

	/**
	 * findList by tags slugs
	 * Sélectionne les éléments comportant les $tags
	 * @param mixed $tagslugs (string/array)
	 */
	public function findListByTagSlug($tagslugs) {
		return $this->findListByTag($tagslugs, 'slug');
	}

	/**
	 * findList by tags noms
	 * Sélectionne les éléments comportant les $tags
	 * @param mixed $tagnoms (string/array)
	 */
	public function findListByTagNom($tagnoms) {
		return $this->findListByTag($tagnoms, 'nom');
	}

	/**
	 * findList by tags
	 * Sélectionne les éléments comportant les $tags
	 * @param mixed $tags (string/array)
	 * @param string $champ
	 */
	public function findListByTag($tags, $champ = 'slug') {
		if(is_string($tags)) $tags = array($tags);
		if(is_array($tags)) {
			$qb = $this->createQueryBuilder('element');
			$qb->join('element.tags', 'tag')
				->where($qb->expr()->in('tag.'.$champ, $tags))
				->orderBy("element.id", "ASC");
		}
		return $qb->getQuery()->getResult();
	}

	/***************************************************************/
	/*** Méthodes conditionnelles
	/***************************************************************/

	/**
	 * genericFilter
	 * Sélect element de statut/expirés/version/non-publié
	 * @param Doctrine\ORM\QueryBuilder $qb
	 * @return QueryBuilder
	 */
	protected function genericFilter(QueryBuilder $qb, $statut = null, $published = true, $expired = true, $version = null) {
		$qb = $this->defaultStatut($qb, $statut);
		$qb = $this->withVersion($qb, $version);
		if($expired === true) $qb = $this->excludeExpired($qb);
		if($published === true) $qb = $this->excludeNotPublished($qb);
		return $qb;
	}

	/**
	 * defaultStatut
	 * Sélect element de statut = actif uniquement
	 * @param Doctrine\ORM\QueryBuilder $qb
	 * @param array/string $statut
	 * @return QueryBuilder
	 */
	protected function defaultStatut(QueryBuilder $qb, $statut = null) {
		if(array_key_exists(self::FIELD_STATUT, $this->getFields())) {
			if($statut === null) $statut = array(self::STATUT_ACTIF);
			if(is_string($statut)) $statut = array($statut);
			$qb->join('element.statut', 'stat')
				->andWhere($qb->expr()->in('stat.nom', $statut));
		}
		return $qb;
	}

	/**
	 * withVersion
	 * Sélect element de version = $version uniquement (slug)
	 * @param Doctrine\ORM\QueryBuilder $qb
	 * @param mixed $version
	 * @return QueryBuilder
	 */
	protected function withVersion(QueryBuilder $qb, $version = null) {
		if(array_key_exists("version", $this->getFields())) {
			if($this->version !== false || $version !== null) {
				if($version !== null) $this->setVersion($version);
				$version = $this->version;
				$qb->join('element.versions', 'ver')
					->andWhere($qb->expr()->in('ver.slug', $version));
			}
		}
		return $qb;
	}

	/**
	 * excludeExpired
	 * Sélect elements non expirés
	 * @param Doctrine\ORM\QueryBuilder $qb
	 * @return QueryBuilder
	 */
	protected function excludeExpired(QueryBuilder $qb) {
		if(array_key_exists(self::FIELD_DATE_EXPIRATION, $this->getFields())) {
			$qb->andWhere('element.dateExpiration > :date OR element.dateExpiration is null')
				->setParameter('date', new \Datetime());
		}
		return $qb;
	}

	/**
	 * excludeNotPublished
	 * Sélect elements publiés
	 * @param Doctrine\ORM\QueryBuilder $qb
	 * @return QueryBuilder
	 */
	protected function excludeNotPublished(QueryBuilder $qb) {
		if(array_key_exists("datePublication", $this->getFields())) {
			$qb->andWhere('element.datePublication < :date OR element.datePublication is null')
				->setParameter('date', new \Datetime());
		}
		return $qb;
	}

	/**
	 * Renvoie les éléments dont les dates sont situées entre $debut et $fin
	 * @param Doctrine\ORM\QueryBuilder $qb
	 * @param Datetime $debut
	 * @param Datetime $fin
	 *
	 */
	protected function betweenDates(QueryBuilder $qb, $debut, $fin, $champ = null) {
		// champ par défaut
		if($champ === null) $champ = self::FIELD_DATE_CREATION;
		// préparations dates
		$tempDates['debut'] = $debut;
		$tempDates['fin'] = $fin;
		foreach($tempDates as $nom => $date) {
			if(is_string($date)) $dates[$nom] = new \Datetime($date);
			if(is_object($date)) $dates[$nom] = $date;
		}
		if(array_key_exists($champ, $this->getFields()) && is_object($dates['debut']) && is_object($dates['fin'])) {
			$qb->andWhere('element.'.$champ.' BETWEEN :debut AND :fin')
				->setParameter('debut', $dates['debut'])
				->setParameter('fin', $dates['fin'])
				;
		}
		return $qb;
	}

	/**
	 * rechercheStr
	 * trouve les éléments qui contiennent la chaîne $searchString
	 *
	 */
	protected function rechercheStr(QueryBuilder $qb, $searchString, $searchField = null, $mode = null) {
		if($searchField === null) {
			$priori = array("nom", "nomunique", "fichierNom");
			$firstField = $this->getFields();
			foreach($priori as $field) if(array_key_exists($field, $firstField)) $searchField = $field;
			if ($searchField === null) $searchField = $firstField[1]['nom'];
		}
		switch ($mode) {
			case 'begin':
				$bef = "";
				$aft = "%";
				break;
			case 'end':
				$bef = "%";
				$aft = "";
				break;
			case 'exact':
				$bef = $aft = "";
				break;
			default:
				$bef = $aft = "%";
				break;
		}
		if(is_string($searchString) && $searchString !== "") {
			$qb->where($qb->expr()->like('element.'.$searchField, $qb->expr()->literal($bef.$searchString.$aft)));
		}
		return $qb;
	}


}
