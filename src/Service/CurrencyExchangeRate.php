<?php

declare(strict_types = 1);

namespace Funyx\CommissionFeeCalculation\Service;

class CurrencyExchangeRate extends HTTPRequest
{
	public string $url = 'https://developers.paysera.com/tasks/api/currency-exchange-rates';

	public function getBody(): array
	{
		return $this->payload['rates'];
	}

	protected function handle(): void
	{
		parent::handle();
		if ( !array_key_exists('rates', $this->payload)) {
			throw new \RuntimeException(sprintf('Failed to load currency-exchange-rates : failed to fetch data from %s missing key %s', $this->url, 'rates'));
		}
	}
}
