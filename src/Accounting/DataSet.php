<?php

declare(strict_types = 1);

namespace Funyx\CommissionFeeCalculation\Accounting;

class DataSet implements \IteratorAggregate
{
	public function __construct( private array $data )
	{
	}

	public function length(): int
	{
		return count($this->data[0]);
	}

	public function getIterator(): \Generator
	{
		foreach ($this->data as $v) {
			yield $v;
		}
	}

	public function jsonEntry(int $n)
	{
		return json_encode($this->data[$n]);
	}
}
