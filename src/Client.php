<?php

namespace PaypalNvpApi;

class Client
{
    protected const BASE_API_LIVE = 'https://api-3t.paypal.com/nvp';
    protected const BASE_API_SANDBOX = 'https://api-3t.sandbox.paypal.com/nvp';

    private $httpWrapper;

    public function __construct(string $username, string $password, string $signature, bool $useSandbox = false)
    {
        $this->httpWrapper = new CurlWrapper(
            $useSandbox ? static::BASE_API_SANDBOX : static::BASE_API_LIVE,
            $username,
            $password,
            $signature
        );
    }


    public function call(string $method, array $params = []): array
    {
        return $this->httpWrapper->prepare($method, $params)->execute();
    }
}
