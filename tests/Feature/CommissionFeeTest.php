<?php
declare(strict_types = 1);

namespace Funyx\CommissionFeeCalculation\Tests\Feature;

use Funyx\CommissionFeeCalculation\Accounting\CommissionFee as CommissionFeeOperation;
use Funyx\CommissionFeeCalculation\Accounting\TransactionList;
use Funyx\CommissionFeeCalculation\Tests\TestCase;

class CommissionFeeTest extends TestCase
{
	/**
	 * @test
	 */
	public function commission_fee_calculation(): void
	{
		$expected = [
			'0.60',
			'3.00',
			'0.00',
			'0.06',
			'1.50',
			'0.00',
			'0.70',
			'0.30',
			'0.30',
			'3.00',
			'0.00',
			'0.00',
			'8611.41'
		];
		$cfo = new CommissionFeeOperation();
		$cfo->setRates([
			'USD' => 1.1497,
			'JPY' => 129.53,
		]);
		foreach ($this->app->run(operation: $cfo, stdout: false) as $k => $l) {
			self::assertSame($l, $expected[$k], sprintf("Test commission fee for transaction %s -> %s", $k, $cfo->getData()->jsonEntry($k)));
		}
	}

	protected function setUp(): void
	{
		parent::setUp();
		$this->app->loadFile(sys_path: __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'mock'.DIRECTORY_SEPARATOR.'input.csv', list_type: TransactionList::class,);
	}
}
