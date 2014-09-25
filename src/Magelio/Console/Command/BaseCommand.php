<?php
namespace Magelio\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Magelio\Console\Command\BaseCommand;

class BaseCommand extends Command
{
  protected function configure()
  {
    $this
      ->addOption('endpoint', 'e', InputOption::VALUE_OPTIONAL,
        'Where the Magento application is. Can be a local.xml or Mage.php file, a Magento root (or not) directory or an APIv2 wsdl endpoint.',
        getcwd()
      )
    ;
  }

}

