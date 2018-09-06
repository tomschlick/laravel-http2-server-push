<?php

namespace TomSchlick\ServerPush\Tests;

use PHPUnit\Framework\TestCase;
use TomSchlick\ServerPush\HttpPush;

/**
 * Class HttpPushTest.
 */
class HttpPushTest extends TestCase
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

    public function test_add_fonts_to_queue()
    {
        $this->instance->queueResource('Roboto.woff', 'font');

        $expected = [
            [
                'path' => 'Roboto.woff',
                'type' => 'font',
            ],
        ];
        $this->assertEquals($expected, $this->instance->resources);
    }

    public function test_add_external_resources_to_queue()
    {
        $this->instance->queueResource('https://example.com/style.css', 'style');

        $expected = [
            [
                'path' => 'https://example.com/style.css',
                'type' => 'style',
            ],
        ];
        $this->assertEquals($expected, $this->instance->resources);
    }

    public function test_add_external_font_to_queue()
    {
        $this->instance->queueResource('https://example.com/Roboto.woff2', 'font');

        $expected = [
            [
                'path' => 'https://example.com/Roboto.woff2',
                'type' => 'font',
            ],
        ];
        $this->assertEquals($expected, $this->instance->resources);
    }

    public function test_resource_generates_link_string()
    {
        $this->instance->queueResource('/assets/app.js.min', 'script');

        $this->assertEquals('</assets/app.js.min>; rel=preload; as=script', $this->instance->generateLinks()[0]);
    }

    public function test_resource_generates_font_link_string()
    {
        $this->instance->queueResource('/assets/font/Roboto.woff', 'font');

        $this->assertEquals('</assets/font/Roboto.woff>; rel=preload; as=font', $this->instance->generateLinks()[0]);
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

        $this->assertEquals('font', (HttpPush::getTypeByExtension('/assets/Roboto.otf')));
        $this->assertEquals('font', (HttpPush::getTypeByExtension('/assets/Roboto.woff')));
        $this->assertEquals('font', (HttpPush::getTypeByExtension('/assets/Roboto.woff2')));
        $this->assertEquals('font', (HttpPush::getTypeByExtension('/assets/Roboto.ttf')));
    }
}
