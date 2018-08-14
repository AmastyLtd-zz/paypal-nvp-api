<?php
namespace PaypalNvpApi;

use PaypalNvpApi\Exception\CurlException;
use PaypalNvpApi\Exception\HttpException;

class CurlWrapper
{
    protected $resource;

    public function __construct(string $url)
    {
        $this->resource = \curl_init();

        \curl_setopt_array($this->resource, [
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_SAFE_UPLOAD => 1,
            CURLOPT_USERAGENT => 'Amasty-Paypal-Nvp-Api',
        ]);
    }

    public function prepare(array $params = []): self
    {
        \curl_setopt($this->resource, CURLOPT_POSTFIELDS, \http_build_query(
            $params,
            null,
            '&',
            \PHP_QUERY_RFC1738
        ));

        return $this;
    }

    public function execute(): string
    {
        $response = \curl_exec($this->resource);

        if (!$response) {
            $errno = curl_errno($this->resource);
            $errstr = curl_error($this->resource);
            throw new CurlException($errno, $errstr,'cURL error');
        }

        $httpCode = \curl_getinfo($this->resource, CURLINFO_HTTP_CODE);
        if ($httpCode !== 200) {
            throw new HttpException($httpCode, "PayPal responded with http code $httpCode");
        }

        return $response;
    }
}
