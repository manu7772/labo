<?php

namespace labo\Bundle\TestmanuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class parametreType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $parametre = new \labo\Bundle\TestmanuBundle\Entity\parametre();

        $builder
            ->add('nom', 'text', array(
                "required"  => true,
                'label'     => 'Nom',
                ))
            ->add('type', 'choice', array(
                "required"  => true,
                "label"     => 'Type de champ',
                'multiple'  => false,
                'expanded'  => false,
                "choices"   => $parametre->getListTypes(),
                ))
            ->add('groupe', 'choice', array(
                "required"  => true,
                "label"     => 'Groupe de paramètres',
                'multiple'  => false,
                'expanded'  => false,
                "choices"   => $parametre->getListGroupes(),
                ))
            ->add('valeur', 'textarea', array(
                'required'  => false,
                'label'     => 'Valeur du paramètre'
                ))
            ->add('optionList', 'textarea', array(
                'required'  => false,
                'label'     => 'Options de valeur (séparer par pipe "|")',
                ))
            // ->add('modifications')
            ->add('descriptif', 'textarea', array(
                'required'  => false,
                'label'     => 'Descriptif du paramètre'
                ))
            // ->add('slug')
            ->add('versions', 'entity', array(
                'class'     => 'LaboTestmanuBundle:version',
                'property'  => 'nom',
                'multiple'  => true,
                'expanded'  => false,
                "label"     => 'Affectations versions'
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'labo\Bundle\TestmanuBundle\Entity\parametre'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'labo_testmanubundle_parametre';
    }
}
