<?php
use PHPUnit\Framework\TestCase;
require_once "src/SwebApi.php";

class SwebApiTest extends TestCase
{
    /**
     * @covers SwebApi::getToken
     * @throws Exception
     */
    public function testGetToken() {
        $api = new SwebApi('ter766terg', 'fZCJ^75ndSdLt3Aq');
        $token = $api->getToken();
        $this->assertIsString($token);
    }

    /**
     * @covers SwebApi::move
     * @throws Exception
     */
    public function testMove() {
        $api = new SwebApi('ter766terg', 'fZCJ^75ndSdLt3Aq');
        $result = $api->move('a15sds4db5ddc1.ru', 'manual');
        $this->assertIsInt($result);
    }
}