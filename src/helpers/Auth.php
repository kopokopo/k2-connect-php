<?php

namespace Kopokopo\SDK\Helpers;

use hash_hmac;

require 'vendor/autoload.php';

class Auth
{
    // TODO: The hash is not returning correct value when mocking; figure out why
    public function auth($details, $signature, $clientSecret)
    {
        $expectedSignature = hash_hmac('sha256', $details, $clientSecret);

        if (hash_equals($signature, $expectedSignature)) {
            return 200;
        } else {
            return 401;
        }
    }
}
