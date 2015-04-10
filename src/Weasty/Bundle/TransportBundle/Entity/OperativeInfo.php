<?php

namespace Weasty\Bundle\TransportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eko\FeedBundle\Item\Reader\ItemInterface as ReaderItemInterface;

/**
 * OperativeInfo
 */
class OperativeInfo implements ReaderItemInterface, \JsonSerializable
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $link;

    /**
     * @var string
     */
    private $guid;

    /**
     * @var string
     */
    private $authorName;

    /**
     * @var string
     */
    private $authorEmail;

    /**
     * @var string
     */
    private $content;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var \DateTime
     */
    private $dateUpdated;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private $categories;

    /**
     * @var array
     */
    private $transportNumbers;

    /**
     * OperativeInfo constructor.
     *
     */
    public function __construct() {
        $this->dateCreated = new \DateTime();
    }

    /**
     * Set categories
     *
     * @param array $categories
     * @return OperativeInfo
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return OperativeInfo
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
     * Set link
     *
     * @param string $link
     * @return OperativeInfo
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return OperativeInfo
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set authorName
     *
     * @param string $authorName
     * @return OperativeInfo
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName
     *
     * @return string 
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorEmail
     *
     * @param string $authorEmail
     * @return OperativeInfo
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * Get authorEmail
     *
     * @return string 
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return OperativeInfo
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

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
     * This method sets feed item title
     *
     * @param string $title
     *
     */
    public function setFeedItemTitle( $title ) {
        $this->setTitle($title);
    }

    /**
     * This method sets feed item description (or content)
     *
     * @param string $description
     *
     */
    public function setFeedItemDescription( $description ) {
        $this
          ->setDescription($description)
          ->setContent($description)
        ;
    }

    /**
     * This method sets feed item URL link
     *
     * @param string $link
     *
     */
    public function setFeedItemLink( $link ) {
        $this->setLink($link);
    }

    /**
     * This method sets item publication date
     *
     * @param \DateTime $date
     *
     */
    public function setFeedItemPubDate( \DateTime $date ) {
        $this->setDateCreated($date);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        // Add your code here
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime|string $dateCreated
     * @return OperativeInfo
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = new \DateTime(date('Y-m-d H:i', ((int)$dateCreated->format('U') + $dateCreated->getOffset())));

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime|string $dateUpdated
     * @return OperativeInfo
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = new \DateTime(date('Y-m-d H:i', ((int)$dateUpdated->format('U') + $dateUpdated->getOffset())));

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return OperativeInfo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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

        $specialChars = ["&nbsp;", "\r", "\n", "\t", " ."];
        $replaceChars = ["", "", "", "", "."];

        $description = trim(str_replace($specialChars, $replaceChars, strip_tags($this->getDescription())));
        $description = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $description);

        $content = trim(str_replace($specialChars, $replaceChars, strip_tags($this->getContent())));
        $content = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $content);

        return [
            'title' => $this->getTitle(),
            'link' => $this->getLink(),
            'authorName' => $this->getAuthorName(),
            'authorEmail' => $this->getAuthorEmail(),
            'description' => $description,
            'content' => $content,
            'categories' => $this->getCategories(),
            'transportNumbers' => $this->getTransportNumbers(),
            'dateCreated' => $this->getDateCreated(),
            'dateUpdated' => $this->getDateUpdated(),
        ];
    }

}
