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
        $k2Sig = 'b3ffb46cb9960b7a8972be1107685e5512c9675e224d8f923eee163c085ad7d0';

        $reqBody = file_get_contents(__DIR__.'/Mocks/hooks/customercreated.json');

        $this->assertEquals(200, $this->auth->auth($reqBody, $k2Sig, $this->clientSecret));
    }
}
