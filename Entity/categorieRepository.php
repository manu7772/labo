<?php

namespace labo\Bundle\TestmanuBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * categorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class categorieRepository extends NestedTreeRepository {
 
	private $arrayResults = array();
	private $em;
	private $version;
	protected $fields = array();
	private $initCMD = false;
	private $ClassMetadata;

	public function __construct(EntityManager $em, ClassMetadata $ClassMetadata) {
		$this->ClassMetadata = $ClassMetadata;
		parent::__construct($em, $this->ClassMetadata);
		$this->em = $em;
		$this->initCMData();
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
		}
	}

	/**
	 * getFields
	 * Liste les champs de l'entité
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * setVersion
	 * fixe la version du site pour le repository
	 * si $version n'est pas précisé, recherche la version par défaut dans l'entité AcmeGroup\LaboBundle\Entity\version
	 * @param string $version
	 * @param string $shutdown
	 * @return categorieRepository
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
	 * @return categorieRepository
	 */
	public function dontTestVersion() {
		$this->version = false;
		return $this;
	}

	/**
	* defaultVal
	* Renvoie toutes les versions disponibles
	*/
	public function defaultVal() {
		// $qb = $this->createQueryBuilder('element');
		// return $qb->getQuery()->getResult();
		return array();
	}

	/**
	* defaultMenu
	* Renvoie l'instance de la version par défaut (ou null)
	*/
	public function defaultMenu() {
		$qb = $this->createQueryBuilder('element')
			->where('element.nom = :nom')
			->setParameter('nom', "catégories")
		;
		$qb = $this->withVersion($qb);
		$qb = $this->defaultStatut($qb);
		return $qb->getQuery()->getOneOrNullResult();
	}

	/**
	* listOfRoots
	* Renvoie les éléments roots
	*/
	public function listOfRoots() {
		$qb = $this->createQueryBuilder('element')
			->where('element.lvl = :val')
			->setParameter('val', 0);
		$qb = $this->withVersion($qb);
		$qb = $this->defaultStatut($qb);
		return $qb->getQuery()->getResult();
	}

	/**
	* listOfMenus
	* Renvoie les éléments pour menus
	*/
	public function listOfMenus() {
		$qb = $this->createQueryBuilder('element')
			->where('element.nommenu != :val')
			->setParameter('val', "");
		$qb = $this->withVersion($qb);
		$qb = $this->defaultStatut($qb);
		return $qb->getQuery()->getResult();
	}


	/**
	* FindNodeBySlug
	* Renvoie l'entité $cSlug
	*
	* @param string $cSlug
	*/
	public function findNodeBySlug($cSlug) {
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.slug = :s')
			->setParameter('s', $cSlug);
		return $qb->getQuery()->getOneOrNullResult();
	}

	/**
	* FindNodeByNom
	* Renvoie l'entité $cNom
	*
	* @param string $cNom
	*/
	public function findNodeByNom($cNom) {
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.nom = :n')
			->setParameter('n', $cNom);
		return $qb->getQuery()->getOneOrNullResult();
	}

	/**
	* findAllNodes
	* Renvoie toutes les entités (array)
	*
	*/
	public function findAllNodes() {
		$qb = $this->createQueryBuilder('element');
		return $qb->getQuery()->getArrayResult();
	}

	/**
	* getSelectListForArticle
	* Liste des catégories pour menu labo articles
	*
	*/
	public function getSelectListForArticle() {
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.parent > :pidi')
			->setParameter('pidi', 1)
			->andWhere('element.parent < :pids')
			->setParameter('pids', 11);
		return $qb;
	}

	/**
	* getSelectListForFicheCreative
	* Liste des catégories pour menu labo articles
	*
	*/
	public function getSelectListForFicheCreative() {
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.parent = :pidi')
			->setParameter('pidi', 15);
		return $qb;
	}

	/**
	* FindChildrenAndBuildArrayTree
	* Renvoie l'arbre des enfants de $cNom (string) sous forme de tableau (array)
	*
	* @param string $c				= entité
	* @param string $version 			= nom de la version (société)
	* @param boolean $allChildren	 	= false : uniquement les enfants directs / true : tous les enfants
	*/
	public function findChildrenAndBuildArrayTree($c, $version, $allChildren = true) {
		$id 	= $c->getId();
		$cNom 	= $c->getNom();
		// return $c->getParent()->getId();

		$qb = $this->createQueryBuilder('element');
		$qb->select('element.nom')
			->where('element.parent = :pid')
			->setParameter('pid', $id);
		$qb = $this->defaultStatut($qb); // sélectionne les éléments actifs uniquement
		$qb = $this->withVersion($qb, $version); // sélectionne les éléments de la version en cours uniquement
		// $r1 = $qb->getQuery()->getArrayResult();
		// foreach($r1 as $ch) $r[$cNom][$ch["nom"]] = $ch["slug"];
		return $qb->getQuery()->getArrayResult();
	}

	/**
	* findNodeArray
	* Renvoie un objet (array) categorie selon $slug
	*
	* @param string $slug
	* @param string $socNom
	*/
	public function findNodeArray($slug, $socNom) {
		$qb = $this->createQueryBuilder('element');
		$qb->where('element.slug = :slug')
			->setParameter('slug', $slug)
			;
		$qb = $this->defaultStatut($qb);
		$qb = $this->withVersion($qb, $socNom);
		return $qb->getQuery()->getArrayResult();
	}

	/**
	* findTreeArray
	* génère l'arbre à partir de $slug
	*
	* @param string $slug
	* @param string $socNom
	*/
	public function findTreeArray($slug, $socNom) {
		// récupère tous les noms des enfants
		$children = $this->childrenArrayListNames($slug);
		//
		$qb = $this->createQueryBuilder('element');
		$qb->where($qb->expr()->in('element.slug', $children))
			->orderBy('element.root, element.lft', 'ASC')
			;
		$qb = $this->defaultStatut($qb);
		$qb = $this->withVersion($qb, $socNom);
		return $qb->getQuery()->getArrayResult();
	}

	public function loadMenuActual($versions) {
		$qb = $this->_em->createQueryBuilder('element');
		$qb->join('element.versions', 's')
			->where($qb->expr()->in('s.nom', $versions))
			->orderBy('element.root, element.lft', 'ASC')
		;
		$r = $qb->getQuery()->getArrayResult();
		return $this->_em->buildTree($r);
	}

	public function getTreeBySlug($slug) {
		$qb = $this->createQueryBuilder('element');
		$qb = $this->defaultStatut($qb);
		   // ->orderBy('element.root, element.lft', 'ASC')
		$qb->where('element.root = :slug')
		   ->setParameter('slug', $slug)
		;
		// $options = array('decorate' => true);
		return $qb->getQuery()->getResult();
	}

	// public function findAllParents($slug) {
	// 	$this->arrayResults = array();
	// 	$qb = $this->createQueryBuilder('c');
	// 	$qb->where('c.slug = :slug')
	// 		->setParameter('slug', $slug);
	// 	$this->arrayResults[] = $qb->getQuery()->getSingleResult();
	// }

	/***************************************************************/
	/*** Méthodes conditionnelles
	/***************************************************************/

	/**
	* childrenArrayListNames
	* Renvoie (array) la liste des noms des enfants de $slug
	*
	* @param string $slug
	* @return array
	*/
	public function childrenArrayListNames($slug) {
		$children = $this->getChildren($slug);
		$ch = array();
		foreach ($children as $child) { $ch[] = $child->getNom(); }
		return $ch;
	}

	/***************************************************************/
	/*** Méthodes conditionnelles
	/***************************************************************/

	/**
	 * findElementsPagination
	 * Recherche les elements en fonction de la version
	 * et pagination avec GET
	 */
	public function findElementsPagination($page = 1, $lignes = null, $ordre = 'id', $sens = 'ASC', $searchString = null, $searchField = "nom") {
		// vérifications pagination
		if($page < 1) $page = 1;
		if($lignes > 100) $lignes = 100;
		if($lignes < 10) $lignes = 10;
		// Requête…
		$qb = $this->createQueryBuilder('element');
		$qb = $this->rechercheStr($qb, $searchString, $searchField);
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
		if(!in_array($ordre, $this->getFields())) $ordre = "id";
		if(!in_array($sens, array('ASC', 'DESC'))) $sens = "ASC";
		$qb->orderBy('element.'.$ordre, $sens);
		// Pagination
		$qb->setFirstResult(($page - 1) * $lignes)
			->setMaxResults($lignes);
		return new Paginator($qb);
	}

	/**
	* genericFilter
	* Sélect element de statut/expirés/version
	* @param Doctrine\ORM\QueryBuilder $qb
	* @return QueryBuilder
	*/
	protected function genericFilter(\Doctrine\ORM\QueryBuilder $qb, $statut = null, $published = true, $expired = true, $version = null) {
		$qb = $this->defaultStatut($qb, $statut);
		$qb = $this->withVersion($qb, $version);
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
			$qb->where($qb->expr()->like('element.'.$searchField, $qb->expr()->literal($bef.$searchString.$aft)));
		}
		return $qb;
	}


}
