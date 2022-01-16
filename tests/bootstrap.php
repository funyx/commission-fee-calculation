<?php

declare(strict_types = 1);

use Funyx\CommissionFeeCalculation\Kernel;

require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$argv = $_SERVER['argv'];
array_shift($argv);

if (is_null(strlen($argv[0]))) {
	throw new RuntimeException('Missing input CSV file');
}

$_SERVER['app'] = new Kernel();
