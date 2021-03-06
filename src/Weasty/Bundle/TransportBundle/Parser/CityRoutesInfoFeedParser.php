<?php
/**
 * Created by PhpStorm.
 * User: ytsialitsyn
 * Date: 4/10/15
 * Time: 2:15 PM
 */

namespace Weasty\Bundle\TransportBundle\Parser;

/**
 * Class CityRoutesInfoFeedParser
 * @package Weasty\Bundle\TransportBundle\Parser
 */
class CityRoutesInfoFeedParser extends TransportInfoFeedParser {
  /**
   * @param $offset
   * @param $limit
   *
   * @return \Zend\Feed\Reader\Feed\FeedInterface
   */
  protected function getFeed( $offset, $limit ) {
    $feedUrl = 'http://www.minsktrans.by/ru/newsall/news/newscity.feed?type=atom&limitstart='.$offset;
    return $this->getFeedReader()->load($feedUrl)->get();
  }
}