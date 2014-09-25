<?php
namespace Magelio\Console;

use Symfony\Component\Console\Application as BaseApplication;

use Magelio\Console\Command\ExportCommand;
use Magelio\Console\Command\ImportCommand;
use Magelio\Console\Command\OverviewCommand;

class Application extends BaseApplication {
  const NAME = 'magelio';
  const VERSION = '0.1';

  public function __construct() {
    parent::__construct(static::NAME, static::VERSION);

    $exportCommand = new ExportCommand();
    $importCommand = new ImportCommand();
    $overviewCommand = new OverviewCommand();

    $this->add($exportCommand);
    $this->add($importCommand);
    $this->add($overviewCommand);
  }
}
