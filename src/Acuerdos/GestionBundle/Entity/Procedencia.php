<?php

namespace Acuerdos\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Procedencia
 *
 * @ORM\Table(name="procedencia")
 * @ORM\Entity(repositoryClass="ProcedenciaRepository")
 */
class Procedencia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="procedencia", type="string", length=200, nullable=false)
     */
    private $procedencia;

    /**
     * @var string
     *
     * @ORM\Column(name="prefijo", type="string", length=5, nullable=false)
     */
    private $prefijo;

    /**
     * @ORM\OneToMany(targetEntity="Acuerdo", mappedBy="idProcedencia", cascade={"persist"})
     */
    private $acuerdos;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set procedencia
     *
     * @param string $procedencia
     * @return Procedencia
     */
    public function setProcedencia($procedencia)
    {
        $this->procedencia = $procedencia;

        return $this;
    }

    /**
     * Get procedencia
     *
     * @return string 
     */
    public function getProcedencia()
    {
        return $this->procedencia;
    }

    /**
     * Set prefijo
     *
     * @param string $prefijo
     * @return Procedencia
     */
    public function setPrefijo($prefijo)
    {
        $this->prefijo = $prefijo;

        return $this;
    }

    /**
     * Get prefijo
     *
     * @return string 
     */
    public function getPrefijo()
    {
        return $this->prefijo;
    }

    public function __toString()
    {
        return $this->getProcedencia();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acuerdos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add acuerdos
     *
     * @param \Acuerdos\GestionBundle\Entity\Acuerdo $acuerdos
     * @return Procedencia
     */
    public function addAcuerdo(\Acuerdos\GestionBundle\Entity\Acuerdo $acuerdos)
    {
        $this->acuerdos[] = $acuerdos;

        return $this;
    }

    /**
     * Remove acuerdos
     *
     * @param \Acuerdos\GestionBundle\Entity\Acuerdo $acuerdos
     */
    public function removeAcuerdo(\Acuerdos\GestionBundle\Entity\Acuerdo $acuerdos)
    {
        $this->acuerdos->removeElement($acuerdos);
    }

    /**
     * Get acuerdos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAcuerdos()
    {
        return $this->acuerdos;
    }
}
