<?php
namespace Weasty\Bundle\TransportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ParseTransportInfoCommand
 * @package Weasty\Bundle\TransportBundle\Command
 */
class ParseTransportInfoCommand extends ContainerAwareCommand {
  protected function configure() {
    $this
      ->setName('transport:parse:info')
    ;
  }

  protected function execute( InputInterface $input, OutputInterface $output ) {

    $parsers = [
      'city_routes_info',
      'village_routes_info',
      'intercity_routes_info',
      'international_routes_info',
      'holiday_transport_info',
      'operative_info',
    ];

    foreach($parsers as $parserName){

      $serviceName = sprintf('weasty_transport.%s.feed.parser', $parserName);
      /**
       * @var $parser \Weasty\Bundle\TransportBundle\Parser\TransportInfoFeedParser
       */
      $parser = $this->getContainer()->get($serviceName);
      $entities = $parser->parse();
      $output->writeln(sprintf('<info>%s - %s posts parsed</info>', count($entities), $parserName));

    }

  }
}