<?php

namespace AcmeGroup\LaboBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// User
use Symfony\Component\Security\Core\SecurityContext;
// Paramétrage de formulaire
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class versionType extends AbstractType {

    private $controller;
    private $securityContext;
    private $parametres;
    
    public function __construct(Controller $controller, $parametres = null) {
        $this->controller = $controller;
        $this->securityContext = $controller->get('security.context');
        if($parametres === null) $parametres = array();
        $this->parametres = $parametres;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nom', 'text')
            // ->add('dateCreation')
            ->add('accroche', 'text', array(
                'required' => false
                ))
            ->add('tvaIntra', 'text', array(
                'required' => false
                ))
            ->add('siren', 'text', array(
                'required' => false
                ))
            ->add('telpublic', 'text', array(
                'required' => false,
                'label' => 'Téléphone'))
            ->add('descriptif', 'textarea', array(
                'required' => false
                ))
            ->add('nomDomaine', 'text', array(
                'label' => 'Nom de domaine'))
            ->add('couleurFond', 'text', array(
                'label' => 'Couleur principale #'))
            ->add('imageEntete', 'entity', array(
                'class'     => 'AcmeGroupLaboBundle:image',
                'property'  => 'nom',
                'multiple'  => false,
                'required'  => true,
                "label"     => 'Image d\'entête',
                'query_builder' => function(\AcmeGroup\LaboBundle\Entity\imageRepository $i) {
                    return $i->findImageByTypes(array('Version'));
                    },
                'empty_value' => 'Sélectionner…'
                ))
            ->add('logo', 'entity', array(
                'class'     => 'AcmeGroupLaboBundle:image',
                'property'  => 'nom',
                'multiple'  => false,
                'required'  => true,
                "label"     => 'Logo',
                'query_builder' => function(\AcmeGroup\LaboBundle\Entity\imageRepository $i) {
                    return $i->findImageByTypes(array('Logo'));
                    },
                'empty_value' => 'Sélectionner…'
                ))
            ->add('favicon', new imageMiniType($this->controller), array(
                "required"  => true,
                "label"     => "Favicon (PNG / JPEG / GIF)"
                ))
            ->add('defaut', 'checkbox', array(
                "label"     => 'Version par défaut',
                'required'  => false
                ))
            ->add('templateIndex', 'text', array(
                'label'     => "Template maître",
                'required'  => true
                ))
        ;
        // $builder = $this->addHiddenValues($builder);

        ///////////////////////////////////////////////
        // Changement du formulaire selon paramètres //
        ///////////////////////////////////////////////
        // $factory = $builder->getFormFactory();
        $user = $this->securityContext->getToken()->getUser();

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($user) {
                $data = $event->getData();
                $form = $event->getForm();

                if(is_object($data) && method_exists($data, "getId")) {
                    if($data->getId() === null) {
                        // L'entité n'existe pas
                        // $event->getForm()->add(
                        //     $factory->createNamed(
                        //         'file', 'file', array("label" => "Fichier (png/jpeg/gif)")
                        //     ));
                    } else {
                        // L'entité existe
                        // $form
                        //  ->remove('file')
                        //  ->add('nom', 'text', array(
                        //      "required"  => false,
                        //      "label"     => 'Nom de l\'image'
                        //  ));
                    }
                }
                // Si ROLE_EDITOR, on change ces champs :
                if(in_array("ROLE_EDITOR", $user->GetRoles())) {
                    //
                }
                // Si ROLE_ADMIN, on change ces champs :
                if(in_array("ROLE_ADMIN", $user->GetRoles())) {
                    //
                }
                // Si ROLE_SUPER_ADMIN, on change ces champs :
                if(in_array("ROLE_SUPER_ADMIN", $user->GetRoles())) {
                    //
                }
            }
        );
    }
    
    /**
     * addHiddenValues
     * @param FormBuilderInterface $builder
     * @return FormBuilderInterface
     */
    public function addHiddenValues(FormBuilderInterface $builder) {
        if(array_key_exists("hidden", $this->parametres)) {
            foreach($this->parametres as $nom => $hidd) {
                if($builder->has($nom)) $builder->remove($nom);
                $builder->add($nom, 'hidden', array(
                    'data' => serialize($hidd)
                ));
            }
        }
        return $builder;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AcmeGroup\LaboBundle\Entity\version'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'acmegroup_labobundle_version';
    }
}
