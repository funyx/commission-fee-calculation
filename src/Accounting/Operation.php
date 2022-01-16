<?php

declare(strict_types = 1);

namespace Funyx\CommissionFeeCalculation\Accounting;

use Funyx\CommissionFeeCalculation\Service\Math;
use JetBrains\PhpStorm\Pure;

abstract class Operation
{
	protected Math $math;
	protected DataSet $data_set;
	protected string $list_type;

	#[Pure] public function __construct( protected int $precision = 2 )
	{
		$this->math = new Math($this->precision);
	}

	public function setData( DataSet $data_set ): void
	{
		$this->data_set = $data_set;
	}

	public function getData(): DataSet
	{
		return $this->data_set;
	}

	public function run(): \Generator
	{
		// TODO
	}

	public function getListType(): string
	{
		return $this->list_type;
	}

	public function setListType( string $list_type ): void
	{
		$this->list_type = $list_type;
	}
}
