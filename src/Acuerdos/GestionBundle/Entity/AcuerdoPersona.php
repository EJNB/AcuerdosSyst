<?php

namespace Acuerdos\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AcuerdoPersona
 *
 * @ORM\Table(name="acuerdo_persona", indexes={@ORM\Index(name="id_persona", columns={"id_persona"}), @ORM\Index(name="id_acuerdo", columns={"id_acuerdo"})})
 * @ORM\Entity(repositoryClass="AcuerdoPersonaRepository")
 */
class AcuerdoPersona
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
     * @ORM\Column(name="tipo", type="string", length=20, nullable=false)
     */
    private $tipo;

    /**
     * @var \Acuerdo
     *
     * @ORM\ManyToOne(targetEntity="Acuerdo", inversedBy="apAcuerdo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_acuerdo", referencedColumnName="ID")
     * })
     */
    private $idAcuerdo;

    /**
     * @var \Persona
     *
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="apPersona")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_persona", referencedColumnName="ID")
     * })
     */
    private $idPersona;



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
     * Set tipo
     *
     * @param string $tipo
     * @return AcuerdoPersona
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set idAcuerdo
     *
     * @param \Acuerdos\GestionBundle\Entity\Acuerdo $idAcuerdo
     * @return AcuerdoPersona
     */
    public function setIdAcuerdo(\Acuerdos\GestionBundle\Entity\Acuerdo $idAcuerdo = null)
    {
        $this->idAcuerdo = $idAcuerdo;

        return $this;
    }

    /**
     * Get idAcuerdo
     *
     * @return \Acuerdos\GestionBundle\Entity\Acuerdo 
     */
    public function getIdAcuerdo()
    {
        return $this->idAcuerdo;
    }

    /**
     * Set idPersona
     *
     * @param \Acuerdos\GestionBundle\Entity\Persona $idPersona
     * @return AcuerdoPersona
     */
    public function setIdPersona(\Acuerdos\GestionBundle\Entity\Persona $idPersona = null)
    {
        $this->idPersona = $idPersona;

        return $this;
    }

    /**
     * Get idPersona
     *
     * @return \Acuerdos\GestionBundle\Entity\Persona 
     */
    public function getIdPersona()
    {
        return $this->idPersona;
    }
}
