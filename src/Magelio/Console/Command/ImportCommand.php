<?php
namespace Magelio\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Magelio\Console\Command\BaseCommand;

class ImportCommand extends BaseCommand
{
  protected function configure()
  {
    parent::configure();
    $this
      ->setName('import')
      ->setDescription('Import data to Magento')
      ->addArgument(
        'source',
        InputArgument::IS_ARRAY,
        'Source data.',
        array('php://stdin')
      )
      ->addOption('has-headers', null, InputOption::VALUE_OPTIONAL, 'Headers in first row.', 'y')
      ->addOption('csv-separator', null, InputOption::VALUE_OPTIONAL, 'CSV separator character.', ',')
      ->addOption('csv-enclosure', null, InputOption::VALUE_OPTIONAL, 'CSV enclosure character.', '"')
      ->addOption('skip', null, InputOption::VALUE_OPTIONAL, 'Lines to skip (from the top of file, including headers).', 0)
      ->addOption('limit', null, InputOption::VALUE_OPTIONAL, '')
      ->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'Initial file position in bytes.', 0)
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {

    if (!$input->hasOption('endpoint')) $input->setOption('endpoint', getcwd());
    $endpoint = $input->getOption('endpoint');
    $sources = $input->getArgument('source');
    

    $hasHeaders = $input->getOption('has-headers');
    
    $csvSeparator = $input->getOption('csv-separator');
    $csvEnclosure = $input->getOption('csv-enclosure');

    $skip = $input->getOption('skip');
    $limit = $input->getOption('limit');
    $offset = $input->getOption('offset');

    $hasHeaders = !(empty($hasHeaders) || in_array(strtolower($hasHeaders)[0], array('0', 'f', 'n')));
    
    $source = reset($sources);
    $lineNumber = 0;
    $handle = new \SplFileObject($source);
    $handle->setCsvControl($csvSeparator, $csvEnclosure);
    $handle->setFlags(\SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

    $headers = null;
    $nHeaders = 0;

    if ($hasHeaders) {
      if (!$handle->eof()) {
        $headers = $handle->fgetcsv();
        $nHeaders = count($headers);
        $lineNumber++;
      }

      if ($limit && !$skip) $limit++;
    }

    if ($offset) $handle->fseek($offset);

    while(!$handle->eof() && $lineNumber < $skip) {
      $line = $handle->fgets();
      $lineNumber++;
    }

    while(!$handle->eof() && ($lineNumber < ($skip + $limit) || !$limit)) {      
      $line = $handle->fgetcsv();
      if ($handle->eof() || !is_array($line)) continue;

      $lineNumber++;
      $tell = $handle->ftell();

      if ($headers) {
        if (count($line) <> $nHeaders) continue;
        $line = array_combine($headers, $line);
      }

      print_r("$lineNumber/$tell/".implode(", ", $line)."\n");
      
    }

    $handle = null;

    

  }
}

