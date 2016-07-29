<?php

namespace TomSchlick\ServerPush\Tests;

use PHPUnit_Framework_TestCase;
use TomSchlick\ServerPush\HttpPush;

/**
 * Class HttpPushTest.
 */
class HttpPushTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HttpPush
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new HttpPush();
    }

    public function tearDown()
    {
        $this->instance->clear();
        parent::tearDown();
    }

    public function test_add_resources_to_queue()
    {
        $this->instance->queueResource('style.css', 'style');

        $expected = [
            [
                'path' => 'style.css',
                'type' => 'style',
            ],
        ];
        $this->assertEquals($expected, $this->instance->resources);
    }

    public function test_resource_generates_link_string()
    {
        $this->instance->queueResource('/assets/app.js.min', 'script');

        $this->assertEquals('</assets/app.js.min>; rel=preload; as=script', $this->instance->generateLinks()[0]);
    }

    public function test_clear_resources()
    {
        $this->instance->queueResource('/assets/app.js.min', 'script');
        $this->instance->queueResource('style.css', 'style');

        $this->instance->clear();
        $this->assertTrue(empty($this->instance->resources));
    }

    public function test_get_type_by_extension()
    {
        $this->assertEquals('script', (HttpPush::getTypeByExtension('/build/app.js')));
        $this->assertEquals('script', (HttpPush::getTypeByExtension('app.min.js')));

        $this->assertEquals('style', (HttpPush::getTypeByExtension('/assets/main.css')));
        $this->assertEquals('image', (HttpPush::getTypeByExtension('/assets/logo.png')));
        $this->assertEquals('image', (HttpPush::getTypeByExtension('/assets/header.gif')));
    }
}
