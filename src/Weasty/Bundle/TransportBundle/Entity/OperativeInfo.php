<?php

namespace Weasty\Bundle\TransportBundle\Entity;

/**
 * Class OperativeInfo
 * @package Weasty\Bundle\TransportBundle\Entity
 */
class OperativeInfo extends TransportInfo
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var array
     */
    protected $transportNumbers;

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
     * Set transportNumbers
     *
     * @param array $transportNumbers
     * @return OperativeInfo
     */
    public function setTransportNumbers($transportNumbers)
    {
        $this->transportNumbers = $transportNumbers;

        return $this;
    }

    /**
     * Get transportNumbers
     *
     * @return array
     */
    public function getTransportNumbers()
    {
        return $this->transportNumbers ?: [
            'bus' => [],
            'trolleybus' => [],
            'tram' => [],
        ];
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize() {

        $data = parent::jsonSerialize();
        $specialChars = ["\r", "\n"];
        $replaceChars = ["", " "];

        $description = trim(str_replace($specialChars, $replaceChars, $data['description']));
        $data['content'] = $description;

        $content = trim(str_replace($specialChars, $replaceChars, $data['content']));
        $data['content'] = $content;

        $data['transportNumbers'] = $this->getTransportNumbers();

        return $data;
    }

}
