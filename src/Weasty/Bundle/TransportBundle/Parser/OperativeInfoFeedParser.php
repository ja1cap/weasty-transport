<?php
/**
 * Created by PhpStorm.
 * User: ytsialitsyn
 * Date: 4/9/15
 * Time: 3:15 PM
 */

namespace Weasty\Bundle\TransportBundle\Parser;

/**
 * Class OperativeInfoFeedParser
 * @package Weasty\Bundle\TransportBundle\Parser
 */
class OperativeInfoFeedParser extends TransportInfoFeedParser {
  /**
   * @param $offset
   * @param $limit
   *
   * @return \Zend\Feed\Reader\Feed\FeedInterface
   */
  protected function getFeed( $offset, $limit ) {
    $feedUrl = 'http://www.minsktrans.by/ru/newsall/news/operativnaya-informatsiya.feed?type=atom&limitstart='.$offset;
    return $this->getFeedReader()->load($feedUrl)->get();
  }

  /**
   * @param string $guid
   * @param \Zend\Feed\Reader\Entry\Atom $entry
   * @param \Weasty\Bundle\TransportBundle\Entity\TransportInfo $entity
   *
   * @return \Weasty\Bundle\TransportBundle\Entity\TransportInfo
   */
  protected function mapFeedEntryParameterToEntity($guid, $entry, $entity){

    /**
     * @var $entity \Weasty\Bundle\TransportBundle\Entity\OperativeInfo
     */
    $entity = parent::mapFeedEntryParameterToEntity($guid, $entry, $entity);

    $transportNumbers = $this->parseContentTransportNumbers($entry->getContent());
    $entity->setTransportNumbers($transportNumbers);

    return $entity;

  }

  /**
   * @param $content
   *
   * @return array
   */
  protected function parseContentTransportNumbers($content){

    $specialChars = ["&nbsp;", "\r", "\n"];
    $replaceChars = [" ", "", ""];

    $filteredContent = str_replace($specialChars, $replaceChars, strip_tags($content));
    //echo($filteredContent).PHP_EOL;

    $transportNumbers = [
      'bus' => [],
      'trolleybus' => [],
      'tram' => [],
    ];

    $contentParts = explode(' ', $filteredContent);
    $transportKeywords = [
      'bus' => 'автобус',
      'trolleybus' => 'троллейбус',
      'tram' => 'трамва',
    ];

    foreach($transportKeywords as $transportName => $transportKeyWord){

      $parseNumbers = null;
      $skipChars = ['#', '№'];
      $numbers = [];

      $invalidCharsCounter = 0;

      foreach($contentParts as $contentPart){

        if(in_array($contentPart, $skipChars)){
          continue;
        }

        if($parseNumbers === null && (strpos($contentPart, $transportKeyWord) !== false)){
          $parseNumbers = true;
        }

        if($parseNumbers){
          if($invalidCharsCounter > 1){
            break;
          }
          //echo $contentPart.PHP_EOL;

          if(strpos($contentPart, ',') !== false) {
            foreach ( explode( ',', $contentPart ) as $number ) {
              if ( $number = $this->filterTransportNumber( $number ) ) {
                $numbers[] = $number;
              }
            }
          } elseif($this->startsWithNumber($contentPart)){
            if ( $number = $this->filterTransportNumber( $contentPart ) ) {
              $numbers[] = $number;
            }
          } elseif($this->startsWithNumberChar($contentPart)){
            if ( $number = $this->filterTransportNumber( $contentPart ) ) {
              $numbers[] = $number;
            }
          } else {
            if($numbers){
              $invalidCharsCounter++;
            }
          }

        }
      }

      $numbers = array_unique($numbers);

      $transportNumbers[$transportName] = $numbers;

    }

    return $transportNumbers;

  }

  /**
   * @param $str
   *
   * @return bool
   */
  protected function startsWithNumberChar($str){
    return (strlen($str) > 1 && strpos($str, '№') === 0);
  }

  /**
   * @param $str
   *
   * @return bool
   */
  protected function startsWithNumber($str){
    return (preg_match('/^\d/', $str) === 1);
  }

  /**
   * @param $number
   *
   * @return mixed|null|string
   */
  protected function filterTransportNumber($number){

    $specialChars = ["№", ".", ",", "&nbsp;", "\r", "\n"];
    $replaceChars = ["", "", "", "", "", ""];

    $number = str_replace($specialChars, $replaceChars, $number);
    $number = trim($number);

    if(!$this->startsWithNumber($number)){
      return null;
    }

    return $number;

  }

}