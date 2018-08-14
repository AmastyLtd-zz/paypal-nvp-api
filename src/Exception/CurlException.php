<?php

namespace PaypalNvpApi\Exception;

class CurlException extends \RuntimeException
{
    private $errNo;
    private $errStr;

    public function __construct(int $errNo, string $errStr, string $message, \Exception $previous = null, ?int $code = 0)
    {
        $this->errNo = $errNo;
        $this->errStr = $errStr;

        parent::__construct($message, $code, $previous);
    }

    public function getErrNo(): int
    {
        return $this->errNo;
    }

    public function getErrStr(): string
    {
        return $this->errStr;
    }
}
