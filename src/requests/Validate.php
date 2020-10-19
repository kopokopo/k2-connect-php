<?php

namespace Kopokopo\SDK\Requests;

class Validate
{
    public function isPhoneValid($phone)
    {
        if (!preg_match('/^\+254\d{9}/', $phone)) {
            throw new \InvalidArgumentException('Invalid phone number format');
        }

        return true;
    }
}
