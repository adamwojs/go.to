<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Encja reprezentująca URL
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Url
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="string", length=12)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * 
     * @Assert\Url();
     * @Assert\Length(max=12)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="target", type="string", length=1024)
     * 
     * @Assert\Url();
     * @Assert\Length(max=1024)
     */
    private $target;

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Url 
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }    
    
    /**
     * Set target
     *
     * @param string $target
     * @return Url
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return string 
     */
    public function getTarget()
    {
        return $this->target;
    }
}
