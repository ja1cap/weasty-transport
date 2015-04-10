<?php

namespace Weasty\Bundle\TransportBundle\Entity;

/**
 * Class VillageRoutesInfo
 * @package Weasty\Bundle\TransportBundle\Entity
 */
class VillageRoutesInfo extends TransportInfo
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
