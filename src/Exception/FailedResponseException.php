<?php

namespace PaypalNvpApi\Exception;

class FailedResponseException extends \RuntimeException
{
    private $response;

    public function __construct(array $response, string $message, \Exception $previous = null, ?int $code = 0)
    {
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): array
    {
        return $this->response;
    }
}
