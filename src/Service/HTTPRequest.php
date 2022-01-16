<?php

declare(strict_types=1);

namespace Funyx\CommissionFeeCalculation\Service;

abstract class HTTPRequest
{
	protected string $url = '/';
	protected int $request_timeout = 5;
	protected string $request_verb = 'GET';
	protected string $response_format = 'application/json';
	protected string $response_charset = 'UTF-8';
	protected mixed $header_size;
	protected string|bool $response;
	protected mixed $payload;

	public function __construct()
	{
		$this->handle();
	}

	protected function handle(): void
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->request_verb);
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2TLS);

		curl_setopt($ch, CURLOPT_HEADER, [
			'Accept: '.$this->response_format.'; charset='.$this->response_charset
		]);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->request_timeout);
		$this->response = curl_exec($ch);
		$this->header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		curl_close($ch);
		unset($ch);

		$payload = substr($this->response, $this->header_size);
		if ('application/json' === $this->response_format) {
			try {
				$payload = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
			} catch (\JsonException $e) {
				throw new \RuntimeException(sprintf('Failed to load json from %s : %s', $this->url, $e->getMessage()));
			}
		}
		$this->payload = $payload;
	}

	public function getBody(): mixed
	{
		return $this->payload;
	}
}
