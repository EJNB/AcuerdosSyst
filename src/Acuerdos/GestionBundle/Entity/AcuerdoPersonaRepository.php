<?php

namespace Acuerdos\GestionBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AcuerdoPersonaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AcuerdoPersonaRepository extends EntityRepository
{
    public function findPersonasAcuerdo($id){

        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();

        $query->select('t1')
            ->from('AcuerdosGestionBundle:AcuerdoPersona', 't1')
            ->where('t1.idAcuerdo = :id')
            ->orderBy('t1.tipo', 'ASC')
            ->setParameter('id',$id)
        ;

        return $query->getQuery()->getResult();

    }

    public function findPersonaTipo($id, $rol){

        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();

        $query->select('t1')
            ->from('AcuerdosGestionBundle:AcuerdoPersona', 't1')
            ->where('t1.idPersona = :id');
            if($rol!='all'){
                $query->andWhere('t1.tipo = :rol')
                      ->setParameter('rol', $rol);
            }
        $query->setParameter('id',$id);

        ;

        return $query->getQuery()->getResult();
    }
}
