<?php

declare(strict_types = 1);

namespace Funyx\CommissionFeeCalculation\Service;

class Math
{
	private int $scale;
	private int $precision;

	public function __construct( int $scale )
	{
		$this->scale = $scale;
		$this->precision = (int)(1 .str_repeat('0', $this->scale));
	}

	public function add( string $leftOperand, string $rightOperand ): string
	{
		return bcadd($leftOperand, $rightOperand, $this->scale);
	}

	public function multiply( string $leftOperand, string $rightOperand ): string
	{
		return number_format(ceil((float)bcmul($leftOperand, $rightOperand, $this->scale + 1) * $this->precision) / $this->precision, $this->scale, thousands_separator: '');
	}

	public function divide( string $leftOperand, string $rightOperand ): string
	{
		return number_format(ceil((float)bcdiv($leftOperand, $rightOperand, $this->scale + 1) * $this->precision) / $this->precision, $this->scale, thousands_separator: '');
	}
}
