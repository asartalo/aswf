<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Functional;

use Asar\Tests\TestCase;
use Asar\Client;
use Asar\Application\Loader;

/**
 * Tests some basic web application features
 */
class BasicsTest extends TestCase
{

    /**
     * Test setup
     */
    public function setUp()
    {
        $this->client = new Client;
        $this->app = Loader::load(__DIR__ . '/ExampleApp/config.yml');
    }

    /**
     * Returns a 404 response when resource does not exist
     */
    public function testReturns404ResponseWhenResourceDoesNotExist()
    {
        $response = $this->client->get($this->app, '/uknown');
        $this->assertEquals(404, $response->getStatus());
    }

    /**
     * A simple test on getting the root resource
     */
    public function testGettingTheHomePage()
    {
        $response = $this->client->get($this->app, '/');
        $this->assertEquals(200, $response->getStatus());
        $this->assertContains('Hello World!', $response->getContent());
    }

    /**
     * Resource uses template
     */
    public function testGetHomepageUsesRepresentation()
    {
        $response = $this->client->get($this->app, '/');
        $this->assertEquals(
            'text/html; charset=utf-8', $response->getHeader('Content-Type')
        );
        $this->assertContains('<h1>Hello World!</h1>', $response->getContent());
    }

    /**
     * Retrieves another page (blog index)
     */
    public function testGetBlogIndexPage()
    {
        $response = $this->client->get($this->app, '/blog');
        $this->assertEquals(200, $response->getStatus());
        $this->assertContains('<h1>The Blog</h1>', $response->getContent());
    }

    /**
     * Pages can use layouts
     */
    public function testIndexPageHasLayout()
    {
        $response = $this->client->get($this->app, '/');
        $this->assertStringStartsWith(
            '<!DOCTYPE html>', $response->getContent()
        );
        $this->assertStringEndsWith(
            '</html>', $response->getContent()
        );
    }
}