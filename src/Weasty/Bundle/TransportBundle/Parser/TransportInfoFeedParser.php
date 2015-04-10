<?php
namespace Weasty\Bundle\TransportBundle\Parser;

/**
 * Class TransportInfoFeedParser
 * @package Weasty\Bundle\TransportBundle\Parser
 */
abstract class TransportInfoFeedParser {

  /**
   * @var \Eko\FeedBundle\Feed\Reader
   */
  protected $feedReader;
  /**
   * @var \Weasty\Bundle\TransportBundle\Entity\TransportInfoRepository
   */
  protected $repository;

  /**
   * @param $offset
   * @param $limit
   *
   * @return \Zend\Feed\Reader\Feed\FeedInterface
   */
  abstract protected function getFeed($offset, $limit);

  /**
   * @return array
   */
  public function parse(){

    $offset = 0;
    $limit = 10;

    /**
     * @var $entities \Weasty\Bundle\TransportBundle\Entity\TransportInfo[]
     */
    $entities = [];
    $repository = $this->getRepository();

    $hasItems = $repository->findOneBy([], []);

    do {

      $feed = $this->getFeed($offset, $limit);

      $feedEntities = $this->parseEntitiesFromFeed($feed);
      if($feedEntities === false){
        break;
      }

      foreach($feedEntities as $entity){
        $entities[$entity->getGuid()] = $entity;
      }

      $offset+=$limit;

    } while ($feed && $feed->count() > 0);

    if(!$hasItems){
      $entities = array_reverse($entities);
    }

    foreach($entities as $entity){
      if(!$entity->getId()){
        $repository->persistEntity($entity);
      }
    }
    $repository->flushEntities($entities);

    return $entities;

  }

  /**
   * @param \Zend\Feed\Reader\Feed\FeedInterface $feed
   *
   * @return \Weasty\Bundle\TransportBundle\Entity\TransportInfo[]
   */
  public function parseEntitiesFromFeed($feed){

    $repository = $this->getRepository();
    $entities = [];

    /**
     * @var $entry \Zend\Feed\Reader\Entry\Atom
     */
    foreach ($feed as $entry) {

      /**
       * @var $dateCreated \DateTime
       */
      $dateCreated = $entry->getDateCreated();
      $guid = ($this->getRepository()->getType().':'.$dateCreated->getTimestamp().':'.md5($entry->getId()));

      $entity = $repository->findOneBy(['guid'=>$guid]);
      if($entity){
        //return false;
      }

      if(!$entity){
        $entity = $repository->createEntity();
      }
      $entity = $this->mapFeedEntryParameterToEntity($guid, $entry, $entity);
      $entities[$guid] = $entity;

    }

    return $entities;

  }


  /**
   * @param string $guid
   * @param \Zend\Feed\Reader\Entry\Atom $entry
   * @param \Weasty\Bundle\TransportBundle\Entity\TransportInfo $entity
   *
   * @return \Weasty\Bundle\TransportBundle\Entity\TransportInfo
   */
  protected function mapFeedEntryParameterToEntity($guid, $entry, $entity){
    $entity
      ->setTitle($entry->getTitle())
      ->setLink($entry->getLink())
      ->setGuid($guid)
      ->setDateCreated($entry->getDateCreated())
      ->setDateUpdated($entry->getDateModified())
      ->setDescription($entry->getDescription())
      ->setContent($entry->getContent())
      ->setCategories($entry->getCategories()->getValues())
    ;

    $author = $entry->getAuthor();
    if($author){
      if(!empty($author['name'])){
        $entity->setAuthorName($author['name']);
      }
      if(!empty($author['email'])){
        $entity->setAuthorEmail($author['email']);
      }
    }

    return $entity;
  }

  /**
   * @return \Eko\FeedBundle\Feed\Reader
   */
  public function getFeedReader() {
    return $this->feedReader;
  }

  /**
   * @param \Eko\FeedBundle\Feed\Reader $feedReader
   */
  public function setFeedReader( $feedReader ) {
    $this->feedReader = $feedReader;
  }

  /**
   * @return \Weasty\Bundle\TransportBundle\Entity\TransportInfoRepository
   */
  public function getRepository() {
    return $this->repository;
  }

  /**
   * @param \Weasty\Bundle\TransportBundle\Entity\TransportInfoRepository $repository
   */
  public function setRepository( $repository ) {
    $this->repository = $repository;
  }

}