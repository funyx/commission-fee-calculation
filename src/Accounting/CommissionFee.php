<?php

declare(strict_types = 1);

namespace Funyx\CommissionFeeCalculation\Accounting;

use Funyx\CommissionFeeCalculation\Service\CurrencyExchangeRate;
use JetBrains\PhpStorm\Pure;

class CommissionFee extends Operation
{
	public const BASE_CURRENCY = 'EUR';

	// fees
	public const BUSINESS_WITHDRAW = '0.005';
	public const DEPOSIT = '0.0003';
	public const PRIVATE_WITHDRAW = '0.003';
	private array $cache = [];
	private array $rates;

	public function run(): \Generator
	{
		foreach ($this->data_set as $transaction) {
			if ( !$transaction) {
				break;
			}

			$operation = $transaction[$this->list_type::OPERATION];
			yield match ($operation) {
				'deposit' => $this->deposit($transaction),
				'withdraw' => $this->withdraw($transaction),
			};
		}
	}

	#[Pure] private function deposit( array $transaction ): string
	{
		$amount = $transaction[$this->list_type::AMOUNT];

		return $this->math->multiply($amount, self::DEPOSIT);
	}

	#[Pure] private function withdraw( array $transaction ): string
	{
		$account_type = $transaction[$this->list_type::ACCOUNT_TYPE];

		return match ($account_type) {
			'private' => $this->privateWithdraw($transaction),
			'business' => $this->businessWithdraw($transaction),
		};
	}

	private function privateWithdraw( array $transaction ): string
	{
		$date = $transaction[$this->list_type::DATE];
		$user_id = $transaction[$this->list_type::USER_ID];
		$amount = $transaction[$this->list_type::AMOUNT];
		$currency = $transaction[$this->list_type::CURRENCY];

		$free_of_charge = 1000;

		if ($currency !== self::BASE_CURRENCY) {
			if (empty($this->rates)) {
				$this->setRates((new CurrencyExchangeRate())->getBody());
			}
			if ( !array_key_exists($currency, $this->rates)) {
				throw new \RuntimeException(sprintf('Missing conversion rate for %s', $currency));
			}
			$amount = (float)$this->math->divide($amount, (string)$this->rates[$currency]);
		}

		if ( !array_key_exists($user_id, $this->cache)) {
			$this->cache[$user_id] = [];
		}

		$year_week = date_create_from_format('Y-m-d', $date)->modify('monday this week')->format('YW');
		if ( !array_key_exists($year_week, $this->cache[$user_id])) {
			$this->cache[$user_id][$year_week]['transactions'] = 1;
			$this->cache[$user_id][$year_week]['total'] = $amount;
			$this->cache[$user_id][$year_week]['free_of_charge'] = true;
		} else {
			$this->cache[$user_id][$year_week]['transactions'] += 1;
			$this->cache[$user_id][$year_week]['total'] += $amount;
		}

		if ($this->cache[$user_id][$year_week]['free_of_charge']) {
			if ($this->cache[$user_id][$year_week]['transactions'] > 3) {
				$this->cache[$user_id][$year_week]['free_of_charge'] = false;
			}
			if ($this->cache[$user_id][$year_week]['total'] >= $free_of_charge) {
				$amount = $this->cache[$user_id][$year_week]['total'] - $free_of_charge;
				$this->cache[$user_id][$year_week]['free_of_charge'] = false;
			}
		}

		$fee = '0.00';
		if ( !$this->cache[$user_id][$year_week]['free_of_charge']) {
			if ($currency !== self::BASE_CURRENCY) {
				$amount = $this->math->multiply((string )$amount, (string)$this->rates[$currency]);
			}
			$fee = $this->math->multiply((string)$amount, self::PRIVATE_WITHDRAW);
		}

		return $fee;
	}

	#[Pure] private function businessWithdraw( array $transaction ): string
	{
		$amount = $transaction[$this->list_type::AMOUNT];

		return $this->math->multiply($amount, self::BUSINESS_WITHDRAW);
	}

	public function setRates( array $rates ): void
	{
		$this->rates = $rates;
	}
}
