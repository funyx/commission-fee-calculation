<?php

declare(strict_types = 1);

use Funyx\CommissionFeeCalculation\Accounting\CommissionFee;
use Funyx\CommissionFeeCalculation\Accounting\TransactionList;
use Funyx\CommissionFeeCalculation\Kernel;

require_once __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$argv = $_SERVER['argv'];
array_shift($argv);

if (is_null(strlen($argv[0]))) {
	throw new RuntimeException('Missing input CSV file');
}

$app = new Kernel();
$app->loadFile(sys_path: __DIR__.DIRECTORY_SEPARATOR.'input.csv', list_type: TransactionList::class);
$app->run(new CommissionFee());
