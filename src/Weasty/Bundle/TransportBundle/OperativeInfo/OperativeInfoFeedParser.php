<?php
/**
 * Created by PhpStorm.
 * User: ytsialitsyn
 * Date: 4/9/15
 * Time: 3:15 PM
 */

namespace Weasty\Bundle\TransportBundle\OperativeInfo;
use Weasty\Bundle\TransportBundle\Entity\OperativeInfo;
use Zend\Feed\Reader\Feed\FeedInterface;

/**
 * Class OperativeInfoFeedParser
 * @package Weasty\Bundle\TransportBundle\OperativeInfo
 */
class OperativeInfoFeedParser {
  /**
   * @var \Eko\FeedBundle\Feed\Reader
   */
  protected $feedReader;
  /**
   * @var \Weasty\Bundle\TransportBundle\Entity\OperativeInfoRepository
   */
  protected $repository;

  public function parse(){

    $reader = $this->getFeedReader();

    $itemsStart = 0;
    $itemsPerRequest = 10;

    $entities = [];
    $repository = $this->getRepository();

    $hasItems = $repository->findOneBy([], []);

    do {

      $feedUrl = 'http://www.minsktrans.by/ru/newsall/news/operativnaya-informatsiya.feed?type=atom&limitstart='.$itemsStart;
      $feed = $reader->load($feedUrl)->get();

      $feedEntities = $this->parseEntitiesFromFeed($feed);
      foreach($feedEntities as $entity){
        $entities[] = $entity;
      }

      $itemsStart+=$itemsPerRequest;

    } while ($feed && $feed->count() > 0);

    if(!$hasItems){
      $entities = array_reverse($entities);
    }

    foreach($entities as $entity){
      $repository->persistEntity($entity);
    }
    $repository->flushEntities($entities);

    return $entities;

  }

  /**
   * @param FeedInterface $feed
   *
   * @return OperativeInfo[]
   */
  public function parseEntitiesFromFeed(FeedInterface $feed){

    $repository = $this->getRepository();
    $entities = [];

    /**
     * @var $entry \Zend\Feed\Reader\Entry\Atom
     */
    foreach ($feed as $entry) {

      $entity = ($repository->findOneBy(['guid'=>$entry->getId()]) ?: new OperativeInfo());

      $entity
        ->setTitle($entry->getTitle())
        ->setLink($entry->getLink())
        ->setGuid($entry->getId())
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

      $entities[] = $entity;
    }

    return $entities;

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
   * @return \Weasty\Bundle\TransportBundle\Entity\OperativeInfoRepository
   */
  public function getRepository() {
    return $this->repository;
  }

  /**
   * @param \Weasty\Bundle\TransportBundle\Entity\OperativeInfoRepository $repository
   */
  public function setRepository( $repository ) {
    $this->repository = $repository;
  }

}