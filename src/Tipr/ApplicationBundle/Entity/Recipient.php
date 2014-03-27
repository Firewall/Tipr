<?php

namespace Tipr\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recipient
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tipr\ApplicationBundle\Entity\RecipientRepository")
 */
class Recipient
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
