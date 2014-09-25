<?php
$classLoader = require __DIR__ . '/vendor/autoload.php';
$classLoader->setUseIncludePath(true);

use Magelio\Console\Application as MagelioConsoleApplication;
$application = new MagelioConsoleApplication();
$application->run();
