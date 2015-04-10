<?php

namespace Weasty\Bundle\TransportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class HolidayTransportInfo
 * @package Weasty\Bundle\TransportBundle\Entity
 */
class HolidayTransportInfo extends TransportInfo
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
