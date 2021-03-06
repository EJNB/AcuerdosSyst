<?php

namespace Acuerdos\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persona
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity(repositoryClass="PersonaRepository")
 */
class Persona
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
     * @ORM\Column(name="descripcion", type="string", length=200, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="cargo", type="string", length=200, nullable=false)
     */
    private $cargo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="AcuerdoPersona", mappedBy="idPersona", cascade={"persist"})
     */
    private $apPersona;



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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Persona
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
     * Set cargo
     *
     * @param string $cargo
     * @return Persona
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string 
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Persona
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->apPersona = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add apPersona
     *
     * @param \Acuerdos\GestionBundle\Entity\AcuerdoPersona $apPersona
     * @return Persona
     */
    public function addApPersona(\Acuerdos\GestionBundle\Entity\AcuerdoPersona $apPersona)
    {
        $this->apPersona[] = $apPersona;

        return $this;
    }

    /**
     * Remove apPersona
     *
     * @param \Acuerdos\GestionBundle\Entity\AcuerdoPersona $apPersona
     */
    public function removeApPersona(\Acuerdos\GestionBundle\Entity\AcuerdoPersona $apPersona)
    {
        $this->apPersona->removeElement($apPersona);
    }

    /**
     * Get apPersona
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApPersona()
    {
        return $this->apPersona;
    }
}
