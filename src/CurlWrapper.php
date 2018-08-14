<?php
namespace PaypalNvpApi;

use PaypalNvpApi\Exception\CurlException;
use PaypalNvpApi\Exception\FailedResponseException;
use PaypalNvpApi\Exception\HttpException;

class CurlWrapper
{
    protected $resource;
    protected $defaultQueryParams;

    public function __construct(string $url, string $username, string $password, string $signature, string $version = '204.0')
    {
        $this->resource = \curl_init();

        curl_setopt($this->resource, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($this->resource, CURLOPT_URL, $url);

        curl_setopt($this->resource, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($this->resource, CURLOPT_HTTPHEADER, [
            'User-Agent: Amasty-Paypal-Nvp-Api',
        ]);

        curl_setopt($this->resource, CURLOPT_SSLVERSION, 6);
        curl_setopt($this->resource, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($this->resource, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($this->resource, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->resource, CURLOPT_POST, 1);
        curl_setopt($this->resource, CURLOPT_SAFE_UPLOAD , 1);

        $this->defaultQueryParams = [
            'USER' => $username,
            'PWD' => $password,
            'SIGNATURE' => $signature,
            'VERSION' => $version,
        ];
    }

    public function prepare(string $method, array $params = []): self
    {
        curl_setopt($this->resource, CURLOPT_POSTFIELDS, \http_build_query(
            array_merge($this->defaultQueryParams, [
                'METHOD' => $method,
            ], $params),
            null,
            '&',
            \PHP_QUERY_RFC1738
        ));

        return $this;
    }

    public function execute(): array
    {
        $response = curl_exec($this->resource);

        if (!$response) {
            $errno = curl_errno($this->resource);
            $errstr = curl_error($this->resource);
            throw new CurlException($errno, $errstr,'cURL error');
        }

        $httpCode = curl_getinfo($this->resource, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new HttpException($httpCode, "PayPal responded with http code $httpCode");
        }

        \parse_str($response, $parsedResponse);

        if ('Success' !== $parsedResponse['ACK']) {
            throw new FailedResponseException($parsedResponse, 'PayPal responded with failed result');
        }

        return $parsedResponse;
    }
}
