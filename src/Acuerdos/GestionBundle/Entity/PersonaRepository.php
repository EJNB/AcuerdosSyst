<?php

namespace Acuerdos\GestionBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PersonaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonaRepository extends EntityRepository
{
    public function  countForAdminList($filter) {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();

        $query->select($query->expr()->count('t1'))
            ->from('AcuerdosGestionBundle:Persona', 't1');

        if ($filter != "") {
            $query->where($query->expr()->like('t1.cargo', '?1'))
                ->orWhere($query->expr()->like('t1.descripcion', '?1'))
                ->setParameter(1, '%' . $filter . '%')
                ;
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function findAllForAdminList($column, $order, $filter, $first_result, $max_result) {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();

        $query->select('t1')
            ->from('AcuerdosGestionBundle:Persona', 't1')
            ->orderBy('t1.' . $column, $order)
            ->setFirstResult($first_result)->setMaxResults($max_result);

        if ($filter != "") {
            $query->where($query->expr()->like('t1.cargo', '?1'))
                ->orWhere($query->expr()->like('t1.descripcion', '?1'))
                ->setParameter(1, '%' . $filter . '%');
        }

        return $query->getQuery()->getResult();
    }

    public function findAllOrdenados(){

        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();

        $query->select('t1')
            ->from('AcuerdosGestionBundle:Persona', 't1')
            ->orderBy('t1.descripcion', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }
}
