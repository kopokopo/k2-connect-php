<?php

namespace Kopokopo\SDK\Helpers;

use hash_hmac;

// require 'vendor/autoload.php';

class Auth
{
    public function auth($details, $signature, $apiKey)
    {
        $expectedSignature = hash_hmac('sha256', $details, $apiKey);

        if (hash_equals($signature, $expectedSignature)) {
            return 200;
        } else {
            return 401;
        }
    }
}
