<?php

namespace Tipr\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Donator
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tipr\ApplicationBundle\Entity\DonatorRepository")
 */
class Donator
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="emailaddress", type="string", length=255)
     */
    private $emailaddress;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\ManyToMany(targetEntity="Tipr\ApplicationBundle\Entity\Donation")
     */
    public $donnations;

    /**
     * @var integer
     *
     * @ORM\Column(name="apiId", type="integer")
     */
    private $api_id;

    /**
     * @param int $api_id
     */
    public function setApiId($api_id)
    {
        $this->api_id = $api_id;
    }

    /**
     * @return int
     */
    public function getApiId()
    {
        return $this->api_id;
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
     * Set emailaddress
     *
     * @param string $emailaddress
     * @return Donator
     */
    public function setEmailaddress($emailaddress)
    {
        $this->emailaddress = $emailaddress;
    
        return $this;
    }

    /**
     * Get emailaddress
     *
     * @return string 
     */
    public function getEmailaddress()
    {
        return $this->emailaddress;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return Donator
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Donator
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

    /**
     * Set surname
     *
     * @param string $surname
     * @return Donator
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $donnations
     */
    public function setDonnations($donnations)
    {
        $this->donnations = $donnations;
    }

    /**
     * @return mixed
     */
    public function getDonnations()
    {
        return $this->donnations;
    }
}
