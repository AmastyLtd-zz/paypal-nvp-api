<?php

namespace PaypalNvpApi\Exception;

class HttpException extends \RuntimeException
{
    private $statusCode;

    public function __construct(int $statusCode, string $message, \Exception $previous = null, ?int $code = 0)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
