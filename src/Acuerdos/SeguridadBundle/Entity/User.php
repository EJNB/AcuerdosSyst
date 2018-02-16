<?php

// src/Acuerdos/SeguridadBundle/Entity/User.php
namespace Acuerdos\SeguridadBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
//use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Acuerdos\SeguridadBundle\Entity\User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserRepository")
 */

class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=128)
     */
    private $password;
    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $salt;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;
    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     *
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->isActive = true;
// may not be needed, see section on salt below
// $this->salt = md5(uniqid(null, true));
    }
    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }
    /**
     * @inheritDoc
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            $this->salt,
        ));
    }
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            $this->salt,
            ) = unserialize($serialized);
    }

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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add roles
     *
     * @param \Acuerdos\SeguridadBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Acuerdos\SeguridadBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Acuerdos\SeguridadBundle\Entity\Role $roles
     */
    public function removeRole(\Acuerdos\SeguridadBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }



    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function isAccountNonExpired()
    {
        return true;
    }
    public function isAccountNonLocked()
    {
        return true;
    }
    public function isCredentialsNonExpired()
    {
        return true;
    }
    public function isEnabled()
    {
        return $this->isActive;
    }
}
