<?php

namespace Acuerdos\GestionBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AcuerdoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idProcedencia', 'entity', array('label' => 'Procedencia: ', 'class' => 'AcuerdosGestionBundle:Procedencia'))
            ->add('idAcuerdo','text', array('label' => 'Codigo del acuerdo: '))
            ->add('estado', 'choice', array(
                'choices' => array(
                    'Pendiente' => 'Pendiente',
                    'Incumplido' => 'Incumplido',
                    'Cumplido' => 'Cumplido',
                    'En_proceso' => 'En proceso',
                    'Permanente' => 'Permanente',
                    'Sin_efecto' => 'Sin efecto'
                )
            ))
            ->add('fechaCreacion', 'date', array('label' => 'Fecha de creacion: ', 'widget' => 'single_text'))
            ->add('descripcion', 'textarea', array(
                'label' => 'Descripción'
            ))
            ->add('fechaCumplimientoIndicada', 'date', array('label' => 'Fecha de Cumplimiento Indicada: ', 'widget' => 'single_text'))
//            ->add('fechaCumplimientoReal', 'date', array('label' => 'Fecha de Cumplimiento Real: ', 'required' => 'false'))
            ->add('accionesSeguimiento', 'textarea', array(
                'label' => 'Acciones de seguimiento',
                'required' => false
            ))
            ->add('observaciones', 'textarea', array(
                'required' => false
            ))


        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Acuerdos\GestionBundle\Entity\Acuerdo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'acuerdos_gestionbundle_acuerdo';
    }
}
