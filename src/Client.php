<?php

namespace PaypalNvpApi;

use PaypalNvpApi\Exception\FailedNvpResponseException;

class Client
{
    protected const BASE_API_LIVE = 'https://api-3t.paypal.com/nvp';
    protected const BASE_API_SANDBOX = 'https://api-3t.sandbox.paypal.com/nvp';
    protected const API_VERSION = '204.0';

    private $httpWrapper;
    private $username;
    private $password;
    private $signature;

    public function __construct(string $username, string $password, string $signature, bool $useSandbox = false)
    {
        $this->httpWrapper = new CurlWrapper($useSandbox ? static::BASE_API_SANDBOX : static::BASE_API_LIVE);
        $this->username = $username;
        $this->password = $password;
        $this->signature = $signature;
    }


    public function call(string $method, array $params = []): array
    {
        $response = $this->httpWrapper->prepare(\array_merge([
            'METHOD' => $method,
            'VERSION' => static::API_VERSION,
            'USER' => $this->username,
            'PWD' => $this->password,
            'SIGNATURE' => $this->signature,
        ], $params))->execute();

        \parse_str($response, $parsedResponse);
        if ('Success' !== $parsedResponse['ACK']) {
            throw new FailedNvpResponseException($parsedResponse, 'PayPal responded with failed result');
        }

        return $parsedResponse;
    }
}
