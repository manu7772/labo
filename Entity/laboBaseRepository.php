<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * laboBaseRepository
 */
class laboBaseRepository extends EntityRepository {

	const ELEMENT = 'element';

	protected $em;
	protected $version = null;
	protected $isBaseRepo;
	protected $fields = array();
	private $initCMD = false;
	private $ClassMetadata;
	protected $defChampDate = "dateCreation";

	protected $entity_shortName;
	protected $entity_className;

	public function __construct(EntityManager $em, ClassMetadata $ClassMetadata) {
		$this->ClassMetadata = $ClassMetadata;
		parent::__construct($em, $this->ClassMetadata);
		$this->em = $em;
		$this->initCMData();
		// $this->setVersion();
		// $qb = $this->createQueryBuilder(self::ELEMENT);
		$this->isBaseRepo = true;
	}

	public function isBaseRepo() {
		return $this->isBaseRepo;
	}

	public function findByField($types, $self = '_self', $asArray = false) {
		$asArray == true ? $getMethod = 'getArrayResult' : $getMethod = 'getResult';
		$qb = $this->createQueryBuilder(self::ELEMENT);
		if(count($types) == 2 || ((count($types) == 3) && ($types['type_related'] == $self))) {
			// champ interne
			$qb = $this->createQueryBuilder(self::ELEMENT)
				->where($qb->expr()->in(self::ELEMENT.'.'.$types['type_field'], $types['type_values']));
			$qb = $this->addJoins($qb, $this->getAdds());
			return $qb->getQuery()->$getMethod();
		} else if(count($types) == 3) {
			// champ externe
			$qb->join(self::ELEMENT.'.'.$types['type_related'], 'entity')
				->where($qb->expr()->in('entity.'.$types['type_field'], $types['type_values']));
			$qb = $this->addJoins($qb, $this->getAdds());
			return $qb->getQuery()->$getMethod();
		}
		return array();
	}

	// public function find($id) {
	// 	$qb = $this->createQueryBuilder(self::ELEMENT);
	// 	// $qb = $this->addJoins($qb, $this->getAdds());
	// 	return $qb->getQuery()->getResult();
	// }

	/**
	 * Entités jointes à ajouter au résultat --> pour addJoins()
	 * @return array
	 */
	protected function getAdds() {
		switch ($this->entity_shortName) {
			case 'article':
				return array(
					"imagePpale" => array(
						"typeImages" => null,
						),
					"images" => array(
						"typeImages" => null,
						),
					"tauxTVA" => null,
					"reseaus" => null,
					"statut" => null,
					);
				break;

			case 'magasin':
				return array(
					"image" => null,
					"statut" => null,
					);
			
			default:
				return array();
				break;
		}
	}

	/**
	 * jointures leftJoin d'après un array
	 * @param QueryBuilder $qb
	 * @param array $adds
	 * @param string $joined = null
	 * @return QueryBuilder
	 */
	protected function addJoins(QueryBuilder $qb, $adds, $joined = null) {
		if($joined === null || !is_string($joined)) $joined = self::ELEMENT;
		if(is_array($adds)) foreach($adds as $field => $childs) {
			$itemField = $joined.'.'.$field;
			if(!is_array($childs)) $childs = array();
			$qb->leftJoin($itemField, $joined.$field)
				->addSelect($joined.$field);
			// echo('$qb->leftJoin('.$itemField.', '.$joined.$field.')<br>->addSelect('.$joined.$field.')'.'<br>');
			if(count($childs) > 0) $qb = $this->addJoins($qb, $childs, $joined.$field);
		}
		return $qb;
	}

	/**
	 * initCMData
	 */
	private function initCMData() {
		if($this->initCMD === false) {
			$this->initCMD = true;
			// ajout champs single
			$fields = $this->ClassMetadata->getColumnNames();
			foreach($fields as $f) {
				$this->fields[$f]['nom'] = $f;
				$this->fields[$f]['type'] = 'single';
			}
			// ajout champs associated
			$assoc = $this->ClassMetadata->getAssociationMappings();
			foreach($assoc as $nom => $field) {
				$this->fields[$nom]['nom'] = $nom;
				$this->fields[$nom]['type'] = 'association';
			}
			// affichage
			// echo("<pre>");print_r($this->fields);echo("</pre>");
			// echo("CDMdata : ".$this->ClassMetadata->getReflectionClass()->getShortName());
			$this->entity_shortName = $this->ClassMetadata->getReflectionClass()->getShortName();
			$this->entity_className = $this->ClassMetadata->getReflectionClass()->getNamespaceName();
		}
	}

	/**
	 * setVersion
	 * fixe la version du site pour le repository
	 * si $version n'est pas précisé, recherche la version par défaut dans l'entité AcmeGroup\LaboBundle\Entity\version
	 * @param string $version
	 * @param string $shutdown
	 */
	public function setVersion($version = null, $shutdown = null) {
		if($version === null) {
			$ver = $this->_em->getRepository("AcmeGroupLaboBundle:version")->defaultVersion();
			$this->version[0] = $ver->getSlug();
		} else if(is_array($version)) {
			$this->version = $version;
		} else if(is_string($version)) {
			$this->version = array($version);
		}
		if($shutdown !== null) $this->version = null;
		return $this;
	}

	/**
	 * getVersion
	 * renvoie la (les) version(s) utilisée par le repository
	 * string si 1 seule version, sinon renvoye un array
	 * @return string/array
	 */
	public function getVersions() {
		return $this->version;
	}

	/**
	 * dontTestVersion
	 * Annule le test de version
	 */
	public function dontTestVersion() {
		$this->version = false;
		return $this;
	}

	/**
	 * getFields
	 * Liste les champs de l'entité
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * findActifs
	 * Liste des éléments de 
	 * - statut = actif
	 * - version = version actuelle
	 * - non expirés
	 * @return array
	 */
	public function findActifs() {
		$qb = $this->createQueryBuilder(self::ELEMENT);
		$qb = $this->defaultStatut($qb);
		$qb = $this->excludeExpired($qb);
		$qb = $this->withVersion($qb);
		// $qb->orderBy('element.id', 'DESC');
		return $qb->getQuery()->getResult();
	}

	/**
	 * Renvoie les entités dont le $champ contient les valeurs $values
	 * @param string $champ
	 * @param array $values
	 * @return array
	 */
	public function findByAttrib($champ, $values) {
		if(is_string($values)) $values = array($values);
		$qb = $this->createQueryBuilder(self::ELEMENT);
		$qb->where($qb->expr()->in(self::ELEMENT.'.'.$champ, $values));
		return $qb->getQuery()->getResult();
	}

	/**
	 * findXrandomElements
	 * récupère $n éléments au hasard dans la BDD
	 * @param integer $n
	 * @return array
	 */
	public function findXrandomElements($n) {
		$n = intval($n);
		if($n < 1) $n = 1;
		$qb = $this->createQueryBuilder(self::ELEMENT);
		$qb = $this->defaultStatut($qb);
		// $qb = $this->excludeExpired($qb);
		$qb = $this->withVersion($qb);
		// $qb->setMaxResults($n);
		$r = $qb->getQuery()->getResult();
		if($n > count($r)) $n = count($r);
		shuffle($r);
		$rs = array();
		for ($i=0; $i < $n ; $i++) { 
			$rs[] = $r[$i];
		}
		return $rs;
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
		$qb = $this->createQueryBuilder(self::ELEMENT);
		$qb = $this->rechercheStr($qb, $pag['searchString'], $pag['searchField']);
		// sous-catégories de tri
		if($souscat !== null) {
			$qb->join(self::ELEMENT.'.'.$souscat['attrib'], 'link')
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
		$qb->orderBy(self::ELEMENT.'.'.$pag['ordre'], $pag['sens']);
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
	// 	$qb = $this->createQueryBuilder(self::ELEMENT);
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
	// 	$qb->orderBy(self::ELEMENT.'.'.$ordre, $sens);
	// 	// Pagination
	// 	$qb->setFirstResult(($page - 1) * $lignes)
	// 		->setMaxResults($lignes);
	// 	return new Paginator($qb);
	// }

	/**
	 * findListByTag
	 * Sélectionne les éléments comportant les $tags
	 * @param mixed (string/array)
	 */
	public function findListByTag($tags) {
		if(is_string($tags)) $tags = array($tags);
		if(is_array($tags)) {
			$qb = $this->createQueryBuilder(self::ELEMENT);
			$qb->join('element.tags', 'tag')
				->where($qb->expr()->in('tag.slug', $tags))
				->orderBy("element.id", "ASC");
		}
		return $qb->getQuery()->getResult();
	}


	/***************************************************************/
	/*** Méthodes conditionnelles
	/***************************************************************/

	/**
	 * genericFilter
	 * Sélect element de statut/expirés/version
	 * @param Doctrine\ORM\QueryBuilder $qb
	 * @return QueryBuilder
	 */
	protected function genericFilter(\Doctrine\ORM\QueryBuilder $qb, $statut = null, $published = true, $expired = true, $version = null) {
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
	protected function defaultStatut(\Doctrine\ORM\QueryBuilder $qb, $statut = null) {
		if(array_key_exists("statut", $this->getFields())) {
			if($statut === null) $statut = array("Actif");
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
	protected function withVersion(\Doctrine\ORM\QueryBuilder $qb, $version = null) {
		if(array_key_exists("versions", $this->getFields())) {
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
	protected function excludeExpired(\Doctrine\ORM\QueryBuilder $qb) {
		if(array_key_exists("dateExpiration", $this->getFields())) {
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
	protected function excludeNotPublished(\Doctrine\ORM\QueryBuilder $qb) {
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
	protected function betweenDates(\Doctrine\ORM\QueryBuilder $qb, $debut, $fin, $champ = null) {
		// champ par défaut
		if($champ === null) $champ = $this->defChampDate;
		// préparations dates
		$tempDates['debut'] = $debut;
		$tempDates['fin'] = $fin;
		foreach($tempDates as $nom => $date) {
			if(is_string($date)) $dates[$nom] = new \Datetime($date);
			if(is_object($date)) $dates[$nom] = $date;
		}
		if(array_key_exists($champ, $this->getFields()) && is_object($dates['debut']) && is_object($dates['fin'])) {
			$qb->andWhere(self::ELEMENT.'.'.$champ.' BETWEEN :debut AND :fin')
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
	protected function rechercheStr(\Doctrine\ORM\QueryBuilder $qb, $searchString, $searchField = null, $mode = null) {
		if($searchField === null) {
			$priori = array("nom", "nommagasin", "nomunique", "fichierNom");
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
			$qb->where($qb->expr()->like(self::ELEMENT.'.'.$searchField, $qb->expr()->literal($bef.$searchString.$aft)));
		}
		return $qb;
	}


}
