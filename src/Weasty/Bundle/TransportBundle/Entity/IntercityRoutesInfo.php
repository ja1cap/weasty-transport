<?php

namespace Weasty\Bundle\TransportBundle\Entity;

/**
 * Class IntercityRoutesInfo
 * @package Weasty\Bundle\TransportBundle\Entity
 */
class IntercityRoutesInfo extends TransportInfo
{
    /**
     * @var integer
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
