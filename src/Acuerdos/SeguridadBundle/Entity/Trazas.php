<?php

namespace Acuerdos\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trazas
 *
 * @ORM\Table(name="trazas", indexes={@ORM\Index(name="id_usuario", columns={"id_usuario"})})
 * @ORM\Entity
 */
class Trazas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="accion", type="string", length=255, nullable=false)
     */
    private $accion;

    /**
     * @var string
     *
     * @ORM\Column(name="elemento", type="string", length=100, nullable=false)
     */
    private $elemento;

    /**
     * @var string
     *
     * @ORM\Column(name="entidad", type="string", length=100, nullable=false)
     */
    private $entidad;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     * })
     */
    private $idUsuario;



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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Trazas
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set accion
     *
     * @param string $accion
     * @return Trazas
     */
    public function setAccion($accion)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion
     *
     * @return string
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set elemento
     *
     * @param string $elemento
     * @return Trazas
     */
    public function setElemento($elemento)
    {
        $this->elemento = $elemento;

        return $this;
    }

    /**
     * Get elemento
     *
     * @return string
     */
    public function getElemento()
    {
        return $this->elemento;
    }

    /**
     * Set entidad
     *
     * @param string $entidad
     * @return Trazas
     */
    public function setEntidad($entidad)
    {
        $this->entidad = $entidad;

        return $this;
    }

    /**
     * Get entidad
     *
     * @return string
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * Set idUsuario
     *
     * @param \Acuerdos\SeguridadBundle\Entity\User $idUsuario
     * @return Trazas
     */
    public function setIdUsuario(\Acuerdos\SeguridadBundle\Entity\User $idUsuario = null)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \Acuerdos\SeguridadBundle\Entity\User
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
}
