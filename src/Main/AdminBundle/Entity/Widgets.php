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

    public function __construct(){
        $this->deleted = 'false';
        $this->template = 'false';
    }

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
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="real_time", type="boolean", nullable=true)
     */
    private $realTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="source", type="integer", nullable=true)
     *
     */
    private $source;

    /**
     * @var integer
     *
     * @ORM\Column(name="chart_type", type="integer", nullable=true)
     *
     */
    private $chartType;

    /**
     * @var integer
     *
     * @ORM\Column(name="query_type", type="integer", nullable=true)
     *
     */
    private $queryType;

    /**
     * @var json_array
     *
     * @ORM\Column(name="code", type="json_array", nullable=true)
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
     * @var boolean
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;

    /**
     * @var boolean
     *
     * @ORM\Column(name="private", type="boolean")
     */
    private $private;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Main\UserBundle\Entity\Users")
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     *
     */
    private $createdBy;

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

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Widgets
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set chartType
     *
     * @param integer $chartType
     * @return Widgets
     */
    public function setChartType($chartType)
    {
        $this->chartType = $chartType;

        return $this;
    }

    /**
     * Get chartType
     *
     * @return integer 
     */
    public function getChartType()
    {
        return $this->chartType;
    }

    /**
     * Set queryType
     *
     * @param integer $queryType
     * @return Widgets
     */
    public function setQueryType($queryType)
    {
        $this->queryType = $queryType;

        return $this;
    }

    /**
     * Get queryType
     *
     * @return integer 
     */
    public function getQueryType()
    {
        return $this->queryType;
    }

    /**
     * Set private
     *
     * @param boolean $private
     * @return Widgets
     */
    public function setPrivate($private)
    {
        $this->private = $private;

        return $this;
    }

    /**
     * Get private
     *
     * @return boolean 
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return Widgets
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
