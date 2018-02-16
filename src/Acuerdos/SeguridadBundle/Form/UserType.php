<?php

namespace Acuerdos\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Nombre completo*'))
            ->add('username', 'text', array('label' => 'Usuario*'))
//            ->add('password')
            ->add('email')
            ->add('isActive', 'checkbox', array(
                'label' => '',
            ))
            ->add('roles', 'entity', array(
                'class' => 'AcuerdosSeguridadBundle:Role',
                'label' => 'Seleccionar roles',
                'expanded' => true,
                'multiple' => true,
                'required' => true,

            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acuerdos\SeguridadBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'acuerdos_seguridadbundle_user';
    }
}
