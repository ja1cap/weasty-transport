<?php
namespace Weasty\Bundle\TransportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ParseOperationInfoCommand
 * @package Weasty\Bundle\TransportBundle\Command
 */
class ParseOperationInfoCommand extends ContainerAwareCommand {
  protected function configure() {
    $this
      ->setName('transport:parse:operative-info')
    ;
  }

  protected function execute( InputInterface $input, OutputInterface $output ) {

    /**
     * @var $parser \Weasty\Bundle\TransportBundle\OperativeInfo\OperativeInfoFeedParser
     */
    $parser = $this->getContainer()->get('weasty_transport.operative_info.feed.parser');
    $entities = $parser->parse();

    $output->writeln(sprintf('<info>%s - operation information posts parsed</info>', count($entities)));

  }
}