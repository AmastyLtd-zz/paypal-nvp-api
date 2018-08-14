<?php
namespace PaypalNvpApi\Tests;

use PaypalNvpApi\Exception\FailedIpnResponseException;
use PaypalNvpApi\IpnVerifier;

class IpnVerifierTest extends \PHPUnit\Framework\TestCase
{
    public function testSuccess(): void
    {
        $ipnVerifier = new IpnVerifier([
            'txn_type' => 'subscr_eot',
            'subscr_id' => 'I-PB2SG17EAY4N',
            'last_name' => 'buyer',
            'residence_country' => 'RU',
            'item_name' => 'magento',
            'mc_currency' => 'RUB',
            'business' => 'wapinet-facilitator@gmail.com',
            'verify_sign' => 'A73WlI1ogLNFXp7p-vKZwC73pe0DAwOhv2xfr.GDuvb3ZB1i4w0I2Hzg',
            'payer_status' => 'verified',
            'test_ipn' => '1',
            'payer_email' => 'wapinet-buyer@gmail.com',
            'first_name' => 'test',
            'receiver_email' => 'wapinet-facilitator@gmail.com',
            'payer_id' => '3SYPBFSL67K3E',
            'custom' => '{"userId":1,"subscriptionPlanId":1}',
            'charset' => 'KOI8_R',
            'notify_version' => '3.9',
            'ipn_track_id' => 'de710926273c4',
        ]);

        $ipnVerifier->validate();
        $data = $ipnVerifier->sanitize();
        $this->assertEquals('UTF-8', $data['charset']);
        $this->assertEquals('KOI8-R', $data['charset_original']);
    }


    public function testFailure(): void
    {
        $ipnVerifier = new IpnVerifier([
            'fake' => 'data'
        ]);

        $this->expectException(FailedIpnResponseException::class);
        $ipnVerifier->validate();
    }
}
