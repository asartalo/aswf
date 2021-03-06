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
use Guzzle\Http\Client;

/**
 * Tests actual web setup
 */
class WebSetupTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->checkWebSetup();
        $this->client = new Client('http://localhost:2973');
    }

    /**
     * Test that the web setup is working
     */
    public function testBasicWebSetupWorking()
    {
        $request = $this->client->get('/');
        $response = $request->send();
        $this->assertContains('<h1>Hello World!</h1>', $response->getBody()->__toString());
    }

    /**
     * Test that we can access other pages
     */
    public function testCanAccessOtherPage()
    {
        $request = $this->client->get('/blog');
        $response = $request->send();
        $this->assertContains('<h1>The Blog</h1>', $response->getBody()->__toString());
    }

    private function checkWebSetup()
    {
        if (getenv('ASWF_DEV_WEBSETUP') !== 'yes') {
            $this->markTestSkipped(
                "To run the full web setup tests, please set the environment variable 'ASWF_DEV_WEBSETUP' to 'yes' to start web setup testing."
            );
        }

        $connection = @fsockopen('localhost', 2973);
        if ($connection === false) {
            $this->markTestSkipped(
                "We are unable to connect to the localhost:2973 for web framework testing. Please see if this is properly setup."
            );
        }
    }

}