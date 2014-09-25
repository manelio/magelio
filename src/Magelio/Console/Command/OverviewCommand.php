<?php
namespace Magelio\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OverviewCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('overview')
      ->setDescription('Overview magento application')
      ->addArgument('type', InputArgument::REQUIRED, 'The type of items to process')
      ->addOption('no-cleanup', null, InputOption::VALUE_NONE)
      ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $output->writeln('<info>I am going to do something very useful</info>');

    require_once('/home/mdomenech/on.prj/0.dvd-dental.com/web/app/Mage.php');
    spl_autoload_unregister(array(\Varien_Autoload::instance(), 'autoload'));

    $mageApp = \Mage::app();

    $websites = $mageApp->getWebsites();
    foreach($websites as $website) {
      print_r($website->getData());
      foreach($website->getGroups() as $group) {
        print_r($group->getData());
        $stores = $group->getStores();
        foreach($stores as $store) {
          print_r($store->getData());
        }
      }
    }

    $model = \Mage::getModel('catalog/product');

    $collection = $model->getCollection();
    foreach($collection as $item) {
      // echo "[".$item->getId()."]";
    }



    // print_r($model);

    $file = $input->getArgument('type');

    echo "[$file]";
  }
}
