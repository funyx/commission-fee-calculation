<?php

declare(strict_types = 1);

namespace Funyx\CommissionFeeCalculation\Accounting;

class TransactionList extends DataSet
{
	public const DATE = 0;
	public const USER_ID = 1;
	public const ACCOUNT_TYPE = 2;
	public const OPERATION = 3;
	public const AMOUNT = 4;
	public const CURRENCY = 5;
}
