<?php

namespace Weasty\Bundle\TransportBundle\Entity;

/**
 * Class CityRoutesInfo
 * @package Weasty\Bundle\TransportBundle\Entity
 */
class CityRoutesInfo extends TransportInfo
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
