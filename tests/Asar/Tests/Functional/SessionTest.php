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

use Asar\TestHelper\TestCase;
use Asar\Client;
use Asar\Application\Loader;

/**
 * Tests session persistence between requests
 */
class SessionTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->client = new Client;
        $this->app = Loader::load(__DIR__ . '/ExampleApp/config.yml');
    }

    /**
     * Sanity check
     */
    public function testSessionTestSanityCheck()
    {
        $response = $this->client->get($this->app, '/session/start');
        $this->assertEquals(200, $response->getStatus());
        $this->assertRegExp('/\w/', $response->getContent()); // Test if pages have content
    }

    /**
     * Test that random data generated is persisted between requests
     */
    public function testThatSomethingIsPersistedBetweenRequests()
    {
        $sessionData1 = $this->client->get($this->app, '/session/start')->getContent();
        $sessionData2 = $this->client->get($this->app, '/session/next')->getContent();
        $this->assertEquals($sessionData1, $sessionData2);
    }

}