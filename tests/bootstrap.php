<?php

declare(strict_types = 1);

use Funyx\CommissionFeeCalculation\Kernel;

require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$_SERVER['app'] = new Kernel();
