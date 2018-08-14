<?php

namespace PaypalNvpApi\Exception;

class FailedIpnResponseException extends \RuntimeException
{
    private $response;

    public function __construct(string $response, string $message, \Exception $previous = null, ?int $code = 0)
    {
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): string
    {
        return $this->response;
    }
}
