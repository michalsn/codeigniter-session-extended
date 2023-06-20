<?php

namespace Tests;

use CodeIgniter\Config\Services;
use CodeIgniter\Test\CIUnitTestCase;
use Michalsn\CodeIgniterSessionExtended\Entities\SessionEntity;

/**
 * @internal
 */
final class SessionEntityTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Services::reset(true);
    }

    public function testSessionEntity(): void
    {
        $data = json_decode('{
            "id": "ci_session:97jpr5fqrsebg17o0ck4kbls45hdcvs5",
            "ip_address": "127.0.0.1",
            "timestamp": "2023-06-17 18:22:51",
            "data": "__ci_last_regenerate|i:1687018971;csrf_test_name|s:32:\"32000c3ed3feda701037efb3a4f92998\";",
            "user_id": "1",
            "user_agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/114.0"
          }', true);

        $se = new SessionEntity($data);

        $this->assertSame(1, $se->user_id);
        $this->assertSame('Mac OS X', $se->platform());
        $this->assertSame('Firefox (114.0)', $se->browser());

    }
}
