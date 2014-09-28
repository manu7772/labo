<?php

namespace labo\Bundle\TestmanuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class tempuserType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $entity = new \AcmeGroup\UserBundle\Entity\User();

        $builder
            ->add('username', "text", array(
                'required'  => true,
                'label'     => "Nom d'utilisateur",
                ))
            ->add('email', "email", array(
                'required'  => true,
                'label'     => "Adresse mail",
                ))
            ->add('mdp1', "password", array(
                'required'  => true,
                'label'     => "Mot de passe",
                ))
            ->add('mdp2', "password", array(
                'required'  => true,
                'label'     => "Mot de passe (vérif.)",
                ))

            // ->add('magasin', 'magasins', array(
            //     // "required"  => true,
            //     // 'class'     => 'LaboTestmanuBundle:magasin',
            //     // 'property'  => 'nommagasin',
            //     // 'multiple'  => false,
            //     "label"     => 'Boutique référente',
            //     // "query_builder" => function(\labo\Bundle\TestmanuBundle\Entity\magasinRepository $magasin) {
            //     //     return $magasin->findAll();
            //     //     }
            //     ))
            // ->add('modelivraison', 'choice', array(
            //     "required"  => true,
            //     'multiple'  => false,
            //     "label"     => 'Mode de livraison',
            //     "choices"   => $entity->getModeslivraison()
            //     ))

            ->add('typemachine', 'text', array(
                "required"  => false,
                "label"     => 'Type/Modèle de machine'
                ))
            ->add('marque', 'entity', array(
                "required"  => false,
                'multiple'  => false,
                "label"     => 'Marque : ',
                'class'     => 'LaboTestmanuBundle:marque',
                'property'  => 'nom',
                ))
            ->add('numserie', 'text', array(
                "required"  => false,
                "label"     => 'Numéro de série'
                ))
            ->add('dateachat', 'datepicker2alldates', array( // --> datepicker : élément de formulaire personnalisé passé en service !!!
                "required"  => false,
                "label"     => 'Date d\'achat'
                ))  
            // ->add('magasin')
            // ->add('modelivraison')
            // ->add('nom')
            // ->add('prenom')
            // ->add('tel')
            // ->add('adresse')
            // ->add('cp')
            // ->add('ville')
            // ->add('typemachine')
            // ->add('marque')
            // ->add('numserie')
            // ->add('dateachat')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'labo\Bundle\TestmanuBundle\Entity\tempuser'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'labo_testmanubundle_tempuser';
    }
}
