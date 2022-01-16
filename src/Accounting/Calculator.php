<?php

declare(strict_types = 1);

namespace Funyx\CommissionFeeCalculation\Accounting;

abstract class Calculator
{
	public const COMMISSION_FEE = 0;

	private array $ops = [
		CommissionFee::class
	];

	private Operation $ctx;

	public function __invoke( int $type, DataSet $data_set )
	{

	}
}
