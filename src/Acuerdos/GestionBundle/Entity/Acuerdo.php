<?php

namespace Acuerdos\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acuerdo
 *
 * @ORM\Table(name="acuerdo", indexes={@ORM\Index(name="id_procedencia", columns={"id_procedencia"})})
 * @ORM\Entity(repositoryClass="AcuerdoRepository")
 */
class Acuerdo
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
     * @ORM\Column(name="id_acuerdo", type="string", length=10, nullable=false)
     */
    private $idAcuerdo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="date", nullable=false)
     */
    private $fechaCreacion;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=500, nullable=false)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_cumplimiento_indicada", type="date", nullable=false)
     */
    private $fechaCumplimientoIndicada;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_cumplimiento_real", type="date", nullable=true)
     */
    private $fechaCumplimientoReal;

    /**
     * @var string
     *
     * @ORM\Column(name="acciones_seguimiento", type="string", length=500, nullable=true)
     */
    private $accionesSeguimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=500, nullable=true)
     */
    private $observaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=20, nullable=false)
     */
    private $estado;

    /**
     * @var \Procedencia
     *
     * @ORM\ManyToOne(targetEntity="Procedencia", inversedBy="acuerdos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_procedencia", referencedColumnName="ID")
     * })
     */
    private $idProcedencia;

    /**
     * @ORM\OneToMany(targetEntity="AcuerdoPersona", mappedBy="idAcuerdo", cascade={"persist"})
     */
    private $apAcuerdo;



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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Acuerdo
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Acuerdo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechaCumplimientoIndicada
     *
     * @param \DateTime $fechaCumplimientoIndicada
     * @return Acuerdo
     */
    public function setFechaCumplimientoIndicada($fechaCumplimientoIndicada)
    {
        $this->fechaCumplimientoIndicada = $fechaCumplimientoIndicada;

        return $this;
    }

    /**
     * Get fechaCumplimientoIndicada
     *
     * @return \DateTime 
     */
    public function getFechaCumplimientoIndicada()
    {
        return $this->fechaCumplimientoIndicada;
    }

    /**
     * Set fechaCumplimientoReal
     *
     * @param \DateTime $fechaCumplimientoReal
     * @return Acuerdo
     */
    public function setFechaCumplimientoReal($fechaCumplimientoReal)
    {
        $this->fechaCumplimientoReal = $fechaCumplimientoReal;

        return $this;
    }

    /**
     * Get fechaCumplimientoReal
     *
     * @return \DateTime 
     */
    public function getFechaCumplimientoReal()
    {
        return $this->fechaCumplimientoReal;
    }

    /**
     * Set accionesSeguimiento
     *
     * @param string $accionesSeguimiento
     * @return Acuerdo
     */
    public function setAccionesSeguimiento($accionesSeguimiento)
    {
        $this->accionesSeguimiento = $accionesSeguimiento;

        return $this;
    }

    /**
     * Get accionesSeguimiento
     *
     * @return string 
     */
    public function getAccionesSeguimiento()
    {
        return $this->accionesSeguimiento;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Acuerdo
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Acuerdo
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set idProcedencia
     *
     * @param \Acuerdos\GestionBundle\Entity\Procedencia $idProcedencia
     * @return Acuerdo
     */
    public function setIdProcedencia(\Acuerdos\GestionBundle\Entity\Procedencia $idProcedencia = null)
    {
        $this->idProcedencia = $idProcedencia;

        return $this;
    }

    /**
     * Get idProcedencia
     *
     * @return \Acuerdos\GestionBundle\Entity\Procedencia 
     */
    public function getIdProcedencia()
    {
        return $this->idProcedencia;
    }

    /**
     * Set idAcuerdo
     *
     * @param string $idAcuerdo
     * @return Acuerdo
     */
    public function setIdAcuerdo($idAcuerdo)
    {
        $this->idAcuerdo = $idAcuerdo;

        return $this;
    }

    /**
     * Get idAcuerdo
     *
     * @return string 
     */
    public function getIdAcuerdo()
    {
        return $this->idAcuerdo;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apAcuerdo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add apAcuerdo
     *
     * @param \Acuerdos\GestionBundle\Entity\AcuerdoPersona $apAcuerdo
     * @return Acuerdo
     */
    public function addApAcuerdo(\Acuerdos\GestionBundle\Entity\AcuerdoPersona $apAcuerdo)
    {
        $this->apAcuerdo[] = $apAcuerdo;

        return $this;
    }

    /**
     * Remove apAcuerdo
     *
     * @param \Acuerdos\GestionBundle\Entity\AcuerdoPersona $apAcuerdo
     */
    public function removeApAcuerdo(\Acuerdos\GestionBundle\Entity\AcuerdoPersona $apAcuerdo)
    {
        $this->apAcuerdo->removeElement($apAcuerdo);
    }

    /**
     * Get apAcuerdo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApAcuerdo()
    {
        return $this->apAcuerdo;
    }
}

class StrAcuerdoPersona{

    private $acuerdo;
    private $personas;

    public function __construct()
    {
        $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getAcuerdo(){
        return $this->acuerdo;
    }

    public function setAcuerdo($Acuerdo){
        $this->acuerdo = $Acuerdo;
    }

    public function setPersonas($Personas){

        $this->personas = $Personas;
    }

    public function getPersonas(){
        return $this->personas;
    }

}
