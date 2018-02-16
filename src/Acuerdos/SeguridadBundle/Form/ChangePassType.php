<?php

namespace Acuerdos\SeguridadBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangePassType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', 'password', array('label' => 'Password'))
                ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Los campos deben coincidir',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repetir Password'),
            ));
    }
    

    /**
     * @return string
     */
    public function getName()
    {
        return 'acuerdos_seguridadbundle_change_pass';
    }
}
