<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit;

use Asar\Tests\TestCase;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;
use Asar\Client;

/**
 * Specifications for Asar\Client
 */
class ClientTest extends TestCase
{
    /**
     * Setup.
     */
    public function setUp()
    {
        $this->app = $this->quickMock('Asar\Http\RequestHandlerInterface');
        $this->client = new Client;
    }

    /**
     * Sends a request handler a request
     */
    public function testSendsARequestToRequestHandler()
    {
        $this->app->expects($this->once())
            ->method('handleRequest')
            ->with(new Request);
        $this->client->get($this->app, '/');
    }

    /**
     * Sends a GET request to request handler with correct path
     */
    public function testSendsAGetRequest()
    {
        $this->app->expects($this->once())
            ->method('handleRequest')
            ->with(new Request(array('path' => '/foo')));
        $this->client->get($this->app, '/foo');
    }

    /**
     * Returns the response from request handler
     */
    public function testReturnsTheResponseFromRequestHandler()
    {
        $response = new Response;
        $this->app->expects($this->once())
            ->method('handleRequest')
            ->will($this->returnValue($response));
        $this->assertSame($response, $this->client->get($this->app, '/'));
    }

}