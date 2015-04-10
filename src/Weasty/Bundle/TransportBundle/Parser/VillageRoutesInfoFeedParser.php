<?php
/**
 * Created by PhpStorm.
 * User: ytsialitsyn
 * Date: 4/10/15
 * Time: 2:15 PM
 */

namespace Weasty\Bundle\TransportBundle\Parser;

/**
 * Class VillageRoutesInfoFeedParser
 * @package Weasty\Bundle\TransportBundle\Parser
 */
class VillageRoutesInfoFeedParser extends TransportInfoFeedParser {
  /**
   * @param $offset
   * @param $limit
   *
   * @return \Zend\Feed\Reader\Feed\FeedInterface
   */
  protected function getFeed( $offset, $limit ) {
    $feedUrl = 'http://www.minsktrans.by/ru/newsall/news/newsprigorod.feed?type=atom&limitstart='.$offset;
    return $this->getFeedReader()->load($feedUrl)->get();
  }
}