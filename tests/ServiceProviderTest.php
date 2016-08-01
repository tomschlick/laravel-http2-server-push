<?php

namespace TomSchlick\ServerPush\Tests;

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use TomSchlick\ServerPush\HttpPush;

class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testHttpPushIsInjectable()
    {
        $this->assertIsInjectable(HttpPush::class);
    }
}