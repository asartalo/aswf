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
     * A simple test on getting the root resource
     */
    public function testGettingTheHomePage()
    {
        $response = $this->client->get($this->app, '/');
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('Hello World!', $response->getContent());
    }
}