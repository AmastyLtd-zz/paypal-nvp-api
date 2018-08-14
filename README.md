# paypal-nvp-api

Simple paypal nvp api integration

[![Build Status Travis](https://travis-ci.org/AmastyLtd/paypal-nvp-api.svg?branch=master)](https://travis-ci.org/AmastyLtd/paypal-nvp-api)


### Requirements:

- PHP >= 7.1.3
- ext-curl


### Installation:
```bash
composer require amasty/paypal-nvp-api
```


### Example
```php
<?php
// @see https://developer.paypal.com/docs/classic/api/
use PaypalNvpApi\Client;

$client = new Client(
    'paypal username',
    'paypal password',
    'paypal signature',
    true // use sandbox
);

// If something is wrong, Exception will be thrown
$client->call('ManageRecurringPaymentsProfileStatus', [
    'PROFILEID' => 'I-PB2SG17EAY4N',
    'ACTION' => 'Cancel',
]);
```

```php
<?php
// @see https://developer.paypal.com/docs/classic/products/instant-payment-notification/
use PaypalNvpApi\IpnVerifier;

$ipnVerifier = new IpnVerifier($_POST);
// If something is wrong, Exception will be thrown
$ipnVerifier->validate();
```
