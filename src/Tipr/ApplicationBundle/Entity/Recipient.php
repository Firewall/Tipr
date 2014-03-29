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
}
