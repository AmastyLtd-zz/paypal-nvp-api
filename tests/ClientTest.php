<?php
namespace PaypalNvpApi\Tests;

use PaypalNvpApi\Client;
use PaypalNvpApi\Exception\FailedResponseException;

class ClientTest extends \PHPUnit\Framework\TestCase
{
    public function testSuccess(): void
    {
        $client = new Client(
            getenv('PAYPAL_USERNAME'),
            getenv('PAYPAL_PASSWORD'),
            getenv('PAYPAL_SIGNATURE'),
            true
        );

        $result = $client->call('GetPalDetails');

        $this->assertEquals('Success', $result['ACK']);
    }


    public function testFailure(): void
    {
        $client = new Client(
            getenv('PAYPAL_USERNAME'),
            getenv('PAYPAL_PASSWORD'),
            getenv('PAYPAL_SIGNATURE'),
            true
        );

        $this->expectException(FailedResponseException::class);
        $client->call('FakeMethod');
    }
}
