<?php

namespace Tipr\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Donation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tipr\ApplicationBundle\Entity\BadgesRepository")
 */
class Badges
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
     * @ORM\Column(name="name", type="string", length=255,nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255,nullable=true)
     */
    private $picture;
}
