<?php

namespace Main\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Widgets
 *
 * @ORM\Table(name="widgets")
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

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="real_time", type="boolean")
     */
    private $realTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="source", type="integer")
     *
     */
    private $source;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     *
     */
    private $type;

    /**
     * @var text
     *
     * @ORM\Column(name="code", type="text")
     *
     */
    private $code;

    /**
     * @var boolean
     *
     * @ORM\Column(name="template", type="boolean")
     *
     */
    private $template;

    /**
     * Set title
     *
     * @param string $title
     * @return Widgets
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set realTime
     *
     * @param boolean $realTime
     * @return Widgets
     */
    public function setRealTime($realTime)
    {
        $this->realTime = $realTime;

        return $this;
    }

    /**
     * Get realTime
     *
     * @return boolean 
     */
    public function getRealTime()
    {
        return $this->realTime;
    }

    /**
     * Set source
     *
     * @param integer $source
     * @return Widgets
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return integer 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Widgets
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Widgets
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set template
     *
     * @param boolean $template
     * @return Widgets
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return boolean 
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
