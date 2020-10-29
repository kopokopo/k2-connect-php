<?php

namespace Kopokopo\SDK\Tests;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Kopokopo\SDK\Helpers\Auth;

class AuthTest extends TestCase
{
    public function setup()
    {
        $this->clientSecret = '10af7ad062a21d9c841877f87b7dec3dbe51aeb3';

        $auth = new Auth();
        $this->auth = $auth;
    }

    public function testAuth()
    {
        $k2Sig = '751295a6998ec61c37a55ca3ae6f5ab5f674615dbee96dd0ee0763cfb04d7f5e';

        $reqBody = file_get_contents(__DIR__.'/Mocks/hooks/customercreated.json');

        $this->assertEquals(200, $this->auth->auth($reqBody, $k2Sig, $this->clientSecret));
    }
}
