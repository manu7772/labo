<?php
// src/AcmeGroup/LaboBundle/eventListeners/sessionData/outPuts3.php

namespace labo\Bundle\TestmanuBundle\eventListeners\sessionData;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
## entités
use labo\Bundle\TestmanuBundle\Entity\article;
use labo\Bundle\TestmanuBundle\Entity\bonLivraison;
use labo\Bundle\TestmanuBundle\Entity\categorie;
use labo\Bundle\TestmanuBundle\Entity\facture;
use labo\Bundle\TestmanuBundle\Entity\image;
use labo\Bundle\TestmanuBundle\Entity\marque;
use labo\Bundle\TestmanuBundle\Entity\pays;
use labo\Bundle\TestmanuBundle\Entity\version;
use labo\Bundle\TestmanuBundle\Entity\sortable;
use labo\Bundle\TestmanuBundle\Entity\statut;
use labo\Bundle\TestmanuBundle\Entity\tauxTVA;
use labo\Bundle\TestmanuBundle\Entity\typeRemise;
use labo\Bundle\TestmanuBundle\Entity\typeImage;
use AcmeGroup\UserBundle\Entity\User;
## Formulaires externalisés
use labo\Bundle\TestmanuBundle\Entity\articleType;
use labo\Bundle\TestmanuBundle\Entity\categorieType;
use labo\Bundle\TestmanuBundle\Entity\imageType;
use labo\Bundle\TestmanuBundle\Entity\marqueType;
use labo\Bundle\TestmanuBundle\Entity\paysType;
use labo\Bundle\TestmanuBundle\Entity\versionType;
use labo\Bundle\TestmanuBundle\Entity\statutType;
use labo\Bundle\TestmanuBundle\Entity\tauxTVAType;
use labo\Bundle\TestmanuBundle\Entity\typeRemiseType;
use labo\Bundle\TestmanuBundle\Entity\typeImageType;

class outPuts3 {

	protected $reqContext;			// page simple (false) ou requête Ajax (true)
	protected $locale;				// langue
	protected $container;			// Objet Container
	protected $twig;				// objet Twig
	protected $em;					// objet entityManager
	protected $requestMethod;		// méthode de requêtes (GET ou POST)
	protected $repo;				// objet entityRepository
	protected $entityObj = array();	// array() des objets entity
	protected $formFactory;			// objet formFactory

	protected $outputs = array();	// éléments à renvoyer en output

	protected $reservedWords = array( // clés non utilisables avec $this->addData()
		"bundleName",		// --> AcmeGroupLaboBundle
		"bundleNameSlash",	// --> AcmeGroup\LaboBundle
		"baseType",			// --> labo\Bundle\TestmanuBundle\Form\
		"basePath",			// --> labo\Bundle\TestmanuBundle\Entity\
		"entityType",		// --> labo\Bundle\TestmanuBundle\Form\categorieType
		"entityPath",		// --> labo\Bundle\TestmanuBundle\Entity\categorie
		"entityRepo"		// --> labo\Bundle\TestmanuBundle\Entity\categorieRepository
		);
	protected $testDefaultVals	= array("statut", "version", "tauxTVA"/*, "typeImage", "pays"*/); // entités à charger par défaut

	public function __construct(EngineInterface $twig, ContainerInterface $container, FormFactoryInterface $formFactory, $locale) {
		$this->locale = $locale;
		$this->twig = $twig;
		$this->container = $container;
		$this->em = $this->container->get('doctrine')->getManager();
		$this->requestMethod = $this->container->get("request")->getMethod();
		$this->formFactory = $formFactory;

		$this->setReqContext($this->container->get('request')->isXmlHttpRequest());
		$this->setBundleName("AcmeGroup", "LaboBundle"); // nom par défaut
		$this->outputs["errors"] = array();
		$this->outputs["html"] = "";
	}

	/**
	* Response
	*
	* @return Response
	* @return JsonResponse
	*/
	public function OPResponse() {
		$this->addHTMLforTWIG();
		$this->outputs["form"] = null;
		if($this->getReqContext()) {
			return new JsonResponse($this->outputs);
		} else {
			return new Response($this->outputs["html"]);
		}
	}

	/**
	* singleTemplateResponse
	*
	* @param string $template
	* @param array $data
	*
	* @return Response
	*/
	public function singleTemplateResponse($template, $data = null) {
		$this->addHTMLforTWIG($template, $data);
		// $this->container->get("session")->getFlashBag()->add('info', "Les flashBag marchent !!!");
		return new Response($this->outputs["html"]);
	}

	/**
	* setReqContext
	*
	* @param array $data
	*
	* @return outPuts3
	*/
	public function setReqContext($reqContext) { // ici on peut forcer le type de requête
		if(is_bool($reqContext)) {
			$this->reqContext = $this->outputs["ajax"] = $reqContext;
		} else $this->ErrorGenerate("Changement de requête mal parametré : boolean requis");
		return $this;
	}

	/**
	* getReqContext
	*
	* @return boolean $reqContext
	*/
	public function getReqContext() {
		return $this->reqContext;
	}

	/**
	* addData
	*
	* @param array $data
	*
	* @return outPuts3
	*/
	public function addData($data = null) {
		if(is_array($data)) {
			foreach($data as $n => $val) {
				if(!in_array($n, $this->reservedWords)) $this->outputs[$n] = $val;
					else $this->ErrorGenerate("\"".$n."\" fait partie des noms réservés / non autorisés");
			}
		}
		return $this;
	}

	/**
	* addMessage
	*
	* @param string $message
	*
	* @return outPuts3
	*/
	public function addMessage($message) {
		if(is_string($message)) {
			$this->outputs["message"] = $message;
		} else $this->ErrorGenerate("Message \"".$message."\" non ajouté : string requis");
		return $this;
	}

	/**
	* addHTMLforTWIG
	*
	* @param string $html
	* @param array $data
	*
	* @return outPuts3
	*/
	public function addHTMLforTWIG($template = null, $data = null) {
		if(is_string($template)) $this->setHtmlTemplate($template);
		$this->addData($data);
		if($this->getHtmlTemplate() !== false)
			$this->outputs["html"] = $this->twig->render($this->outputs["template"], array("data" => $this->outputs));
		return $this;
	}

	/**
	* addResult
	*
	* @param boolean $result
	*
	* @return outPuts3
	*/
	public function addResult($result) {
		if(is_bool($result)) {
			$this->outputs["result"] = $result;
		} else {
			$this->outputs["result"] = false;
			$this->ErrorGenerate("Result non attribué : boolean requis");
		}
		return $this;
	}

	/**
	* setHtmlTemplate
	*
	* @param string $template
	*
	* @return outPuts3
	*/
	public function setHtmlTemplate($template) {
		$this->outputs["template"] = $template;
		return $this;
	}

	/**
	* getHtmlTemplate
	*
	* @return template (nom)
	*/
	public function getHtmlTemplate() {
		if(isset($this->outputs["template"])) return $this->outputs["template"];
			else return false;
	}

	/**
	* setBundleName
	* Change de groupe et/ou de bundle
	* @param $groupe
	* @param $bundle
	*
	* @return outPuts3
	*/
	public function setBundleName($groupe, $bundleName) {
		if(is_string($groupe) && is_string($bundleName)) {
			$this->outputs["bundleName"] = $groupe.$bundleName;
			$this->outputs["bundleNameSlash"] = $groupe."\\".$bundleName;
			$this->outputs["baseType"] = $this->outputs["bundleNameSlash"].'\Form\\';
			$this->outputs["basePath"] = $this->outputs["bundleNameSlash"].'\Entity\\';
		} else $this->ErrorGenerate("Nom de bundle invalide : non attribué");
		return $this;
	}
		// "bundleName",		// --> AcmeGroupLaboBundle
		// "bundleNameSlash",	// --> AcmeGroup\LaboBundle
		// "baseType",			// --> labo\Bundle\TestmanuBundle\Form\
		// "basePath",			// --> labo\Bundle\TestmanuBundle\Entity\

	/**
	* setEntity
	* Change d'entity
	* @param $entity
	* @param $repoMethod = nom de la méthode Repository à utiliser
	* @param $repoParams = paramètres nécessaire à la méthode $repoMethod
	*
	* @return outPuts3
	*/
	public function setEntity($entity, $repoMethod = null, $repoParams = null) {
		if(is_string($entity) && is_string($this->outputs["bundleName"])) {
			$this->outputs["repoMethod"] = $repoMethod;
			$this->outputs["repoParams"] = $repoParams;
			$this->outputs["entity"] = $entity;
			$this->outputs["entityType"] = $this->outputs["baseType"].$this->outputs["entity"]."Type";
			$this->outputs["entityPath"] = $this->outputs["basePath"].$this->outputs["entity"];
			$this->outputs["entityRepo"] = $this->outputs["basePath"].$this->outputs["entity"]."Repository";
			$this->repo = $this->em->getRepository($this->outputs["bundleName"].':'.$this->outputs["entity"]);
		} else {
			if($this->outputs["entity"] === null) $this->ErrorGenerate("Nom d'entité invalide et non défini : echec");
		}
		return $this;
	}
		// "entityType",		// --> labo\Bundle\TestmanuBundle\Form\categorieType
		// "entityPath",		// --> labo\Bundle\TestmanuBundle\Entity\categorie
		// "entityRepo"			// --> labo\Bundle\TestmanuBundle\Entity\categorieRepository

	/**
	* getEntityName
	*
	* @return entityName
	*/
	public function getEntityName() {
		return $this->outputs["entity"];
	}

	/**
	* getEntityObject
	*
	* @return entityObj
	*/
	public function getEntityObject() {
		return $this->entityObj[$this->getEntityName()];
	}

	/**
	* ErrorGenerate
	*
	* @param $txt
	*/
	private function ErrorGenerate($txt) {
		$this->outputs["errors"][] = $txt;
		return $this;
	}

	/**
	* entityAction
	* Gestion sur les entités et le formulaire
	* @param $action
	* @param $entity
	* @param $id
	*
	* @return outPuts3
	*/
	public function entityAction($action = "voir", $entity = null, $id = null) {
		$this->setEntity($entity);
		$this->addData(array(
			"action" => $action,
			"id" => intval($id)
			));
		$entityName = $this->getEntityName();
		switch($action) {
			case "editer" :
			case "ajouter" :
				if($this->outputs["action"] == 'editer' && $this->outputs["id"] > 0) {
					// mode "editer"
					$this->entityObj[$entityName] = $this->repo->find($this->outputs["id"]);
					// Si l'id n'a pas été trouvé, passage en mode "ajouter"
					if(!is_object($this->entityObj[$entityName])) {
						$this->outputs["action"] = 'ajouter';
						$this->entityObj[$entityName] = new $this->outputs["entityPath"];
					}
				} else {
					// mode "ajouter"
					$this->outputs["action"] = 'ajouter';
					$this->entityObj[$entityName] = new $this->outputs["entityPath"];
				}
				$form = $this->formPrepare($this->outputs["id"]); // Génère le formulaire en plaçant les valeurs par défaut
				if($this->requestMethod == "POST") {
					// Formulaire en retour
					// $validator = $this->container->get('validator');
					$form->bind($this->container->get('request'));
					if($form->isValid()) {
						$this->addResult(true);
						$this->addMessage("Données enregistrées");
						$this->outputs["form"] = $form->createView();
						$this->em->persist($this->entityObj[$entityName]);
						$this->em->flush();
					} else {
						$this->addResult(false);
						$this->ErrorGenerate("Données incomplètes");
						$this->outputs["form"] = $form->createView();
					}
				} else {
					// Premier formulaire
					$this->addResult(true);
					$this->outputs["form"] = $form->createView();
				}
				break;
			case "supprimer" :
				$r = $this->SuppEntity($this->outputs["id"]);
				if($r) {
					$this->addResult(true);
					$this->addMessage("Elément supprimé");
				} else {
					$this->addResult(false);
					$this->addMessage("L'élément est introuvable et n'a pu être supprimé");
				}
				break;
			default : // voir, etc.
				$repoMethod = "findAll";
				if($this->outputs["repoMethod"] !== null) $repoMethod = $this->outputs["repoMethod"];
				$this->addResult(true);
				$this->addData(array("entities" => array(
					$this->getEntityName() => array(
						"data"		=> $this->repo->$repoMethod($this->outputs["repoParams"]),
						"fields"	=> $this->entityFieldList()
					)
				)));
				break;
		}
		return $this;
	}

	/**
	* formPrepare
	* Génère le formulaire depuis une entité en affectant les valeurs par défaut
	* @param $id : id de l'entité (mode "editer") sinon null = mode "ajouter"
	*/
	public function formPrepare($id) {
		// mode ajouter + premier formulaire = ajoute les valeurs par défaut
		$entityName = $this->getEntityName();
		if($id == 0 && $this->requestMethod !== "POST") {
			$result = array();
			foreach($this->testDefaultVals as $searchEntity) {
				$search = $this->em->getRepository($this->outputs["bundleName"].':'.$searchEntity);
				if(method_exists($search, "defaultVal")) {
					$result[$searchEntity] = $search->defaultVal();
					$methCall = "set".ucfirst($searchEntity);
					if($result[$searchEntity] != null && method_exists($this->entityObj[$entityName], $methCall))
						$this->entityObj[$entityName]->$methCall($result[$searchEntity][0][0]);
				}
			}
		}
		// Génération du formulaire
		return $this->formFactory->create(new $this->outputs["entityType"], $this->entityObj[$entityName]);
	}

	/**
	* SuppEntity
	*
	* @return boolean
	*/
	private function SuppEntity($id) {
		$elem = $this->repo->find($id);
		if(is_object($elem)) {
			if($this->getEntityName() == "image"){
				$elem->supprimerFichiers();
			}
			$this->em->remove($elem);
			$this->em->flush();
			return true;
		} else return false;
	}

	/**
	* entityFieldList
	*
	* @return form
	*/
	private function entityFieldList() {
		// if($this->outputs["entityPath"] !== null) {
		// 	$enti = new $this->outputs["entityPath"];
		// 	$this->outputs["entityType"] = $this->container->createForm(new $this->outputs["entityType"], $enti);

		// }
		return array("id", "nom", "descriptif", "notation", "prix", "dateCreation", "fichierOrigine");
	}


	/*************************************************/
	/******************     TREE     *****************/
	/*************************************************/

	public function loadTreeEntity($entity, $slug) {
		if($entity !== null) $this->setEntity($entity);
		$elem = $this->repo->findOneBySlug($slug);
		$this->outputs["action"] = "voir";
		$this->outputs["categorie"] = $elem;
		$this->outputs["menu"] = $this->repo->childrenHierarchy();
		$this->outputs["menuBySlug"] = $this->repo->childrenHierarchy($elem);
		$this->outputs["path"] = $this->repo->getPath($elem);
		$this->outputs["children"] = $this->repo->getChildren($elem);
		return $this;
	}

	public function loadActualTreeEntity($entity = null) {
		if($entity !== null) $this->setEntity($entity);
		$version = $this->container->get('session')->get('version');
		$this->outputs['test_soc_nom'] = unserialize($version);
		$socNom[0] = "Actif";

		$qb = $this->em->createQueryBuilder();
		$qb->select('c')
			->from('AcmeGroupLaboBundle:categorie', 'c')
			->join('c.statut', 's')
			->where($qb->expr()->in('s.nom', $socNom))
			// ->join('c.statut', 'st', 'WITH', 'UPPER(st.nom) = :actif')
			// ->setParameter('actif', 'ACTIF')
			->orderBy('c.root, c.lft', 'ASC')
		;
		$r = $qb->getQuery()->getArrayResult();


		$this->outputs["menu2"] = $this->repo->buildTree($r);
		return $this;
	}

	/**
	* treeAction
	*
	* @param $action
	* @param $entity
	* @param $data : string = slug de référence pour opération $action
	*/
	public function treeAction($action = "voir", $entity = null, $slug = "categories") {
		$this->setEntity($entity);
		$this->addData(array(
			"action" => $action,
			"slug" => $slug
			));
		$entityName = $this->getEntityName();
		switch ($action) {
			case 'ajouter':
				$this->outputs["id"] = 0;
				// $parent = $this->repo->findOneBySlug($slug);
				$this->outputs["action"] = 'ajouter';
				$this->entityObj[$entityName] = new $this->outputs["entityPath"];
				$form = $this->formPrepare($this->outputs["id"]); // Génère le formulaire en plaçant les valeurs par défaut
				if($this->requestMethod == "POST") {
					// Formulaire en retour
					// $validator = $this->container->get('validator');
					$form->bind($this->container->get('request'));
					if($form->isValid()) {
						$this->addResult(true);
						$this->addMessage("Données enregistrées");
						$this->outputs["form"] = $form->createView();
						// $this->entityObj[$entityName]->setParent($parent); // --> désignation du parent
						$this->repo->persistAsLastChildOf($this->entityObj[$entityName], $this->repo->findOneBySlug($slug));
						$this->em->flush();
					} else {
						$this->addResult(false);
						$this->ErrorGenerate("Données incomplètes");
						$this->outputs["form"] = $form->createView();
					}
				} else {
					// Premier formulaire
					$this->addResult(true);
					$this->outputs["form"] = $form->createView();
				}
				break;
			case 'moveUp':
				$elem = $this->repo->findOneBySlug($slug);
				$this->repo->moveUp($elem);
				$this->addResult(true);
				$this->addMessage("Elément remonté");
				break;
			case 'moveDown':
				$elem = $this->repo->findOneBySlug($slug);
				$this->repo->moveDown($elem);
				$this->addResult(true);
				$this->addMessage("Elément descendu");
				break;
			case 'effacer':
				$elem = $this->repo->findOneBySlug($slug);
				$this->repo->remove($elem);
				$this->addResult(true);
				$this->addMessage("Elément supprimé");
				break;
			default: // voir, etc.
				$elem = $this->repo->findOneBySlug($slug);
				$this->outputs["categorie"] = $elem;
				$this->outputs["menu"] = $this->repo->childrenHierarchy();
				$this->outputs["menuBySlug"] = $this->repo->childrenHierarchy($elem);
				$this->outputs["path"] = $this->repo->getPath($elem);
				$this->outputs["children"] = $this->repo->getChildren($elem);
				$this->addResult(true);
				break;
		}
		return $this;
	}





}

?>
