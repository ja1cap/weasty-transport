<?php

namespace Weasty\Bundle\TransportBundle\Entity;

/**
 * Class InternationalRoutesInfo
 * @package Weasty\Bundle\TransportBundle\Entity
 */
class InternationalRoutesInfo extends TransportInfo
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
