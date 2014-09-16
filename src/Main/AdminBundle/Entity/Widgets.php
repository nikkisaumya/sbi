<?php

namespace Main\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Widgets
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Main\AdminBundle\Entity\WidgetsRepository")
 */
class Widgets
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
