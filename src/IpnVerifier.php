<?php
namespace PaypalNvpApi;

use PaypalNvpApi\Exception\FailedIpnResponseException;


/**
 * @see https://www.geekality.net/2011/05/28/php-tutorial-paypal-instant-payment-notification-ipn/
 */
class IpnVerifier
{
    protected const PAYPAL_URL = 'https://www.paypal.com/cgi-bin/webscr';
    protected const PAYPAL_SANDBOX_URL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    protected $ipnPostData;
    protected $useSandbox;

    /**
     * @param array $ipnPostData raw ipn post data from paypal ($_POST)
     */
    public function __construct(array $ipnPostData)
    {
        $this->ipnPostData = $ipnPostData;
        $this->useSandbox = \array_key_exists('test_ipn', $this->ipnPostData) && 1 === (int)$ipnPostData['test_ipn'];
    }

    /**
     * Validates IPN data.
     *
     * [!!] Verification will fail if the data has been alterend in *any* way.
     *
     * @return $this
     */
    public function validate(): self
    {
        $curl = new CurlWrapper($this->useSandbox ? static::PAYPAL_SANDBOX_URL : static::PAYPAL_URL);
        $curl->prepare(['cmd' => '_notify-validate'] + $this->ipnPostData);
        $response = $curl->execute();

        if ('VERIFIED' !== $response) {
            throw new FailedIpnResponseException($response, 'PayPal responded with failed result');
        }

        return $this;
    }

    public function sanitize(): array
    {
        // Just return empty array if empty
        if (!$this->ipnPostData) {
            return [];
        }

        // Fix encoding
        $this->fixEncoding($this->ipnPostData);

        // Sort keys (easier to debug)
        ksort($this->ipnPostData);

        return $this->ipnPostData;
    }

    protected function fixEncoding(&$ipnData): void
    {
        // If charset is specified
        if (array_key_exists('charset', $ipnData) && $ipnData['charset']) {
            $charset = str_replace('_', '-', $ipnData['charset']); // KOI8_R

            // Ignore if same as our default
            if (strtoupper($charset) === 'UTF-8') {
                return;
            }

            // Otherwise convert all the values
            foreach ($ipnData as $key => &$value) {
                $value = \mb_convert_encoding($value, 'UTF-8', $charset);
            }

            // And store the charset values for future reference
            $ipnData['charset'] = 'UTF-8';
            $ipnData['charset_original'] = $charset;
        }
    }
}
