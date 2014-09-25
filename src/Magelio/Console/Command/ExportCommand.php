<?php
namespace Magelio\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Magelio\Service\ExportService;

class ExportCommand extends Command
{
  protected function configure()
  {

    parent::configure();
    $this
      ->setName('export')
      ->setDescription('Export data from Magento')      
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {


    $endpoint = $input->getArgument('endpoint');

    $service = new ExportService();
    $service->endpoint = $endpoint;

    $service->export();
  }
}
