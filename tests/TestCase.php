<?php

declare(strict_types=1);

namespace Funyx\CommissionFeeCalculation\Tests;

/** @property  \Funyx\CommissionFeeCalculation\Kernel $app */
class TestCase extends \PHPUnit\Framework\TestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->app = $_SERVER['app'];
	}
}
