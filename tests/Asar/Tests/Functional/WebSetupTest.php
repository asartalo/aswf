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

use Guzzle\Http\Client;
use Asar\Tests\TestCase;

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
     * Test that the php setup is working
     */
    public function testBasicWebSetupWorking()
    {
        $request = $this->client->get('/');
        $response = $request->send();
        $this->assertContains('Hello World!', $response->getBody()->__toString());
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