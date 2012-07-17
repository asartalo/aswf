<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Application;

use Asar\Tests\TestCase;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;
use Asar\Application\Application;

/**
 * Specifications for Asar\Application\Application
 */
class ApplicationTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->router = $this->quickMock(
            'Asar\Routing\RouterInterface', array('route')
        );
        $this->app = new Application($this->router);
    }

    /**
     * Passes request path to router
     */
    public function testPassesRequestPathToRouter()
    {
        $this->router->expects($this->once())
            ->method('route')
            ->with('/foo');
        $this->app->handleRequest(new Request(array('path' => '/foo')));
    }

    /**
     * Passes request to resource found by router
     */
    public function testPassesRequestToResourceFoundByRouter()
    {
        $request = new Request(array('path' => '/foo'));
        $resource = $this->quickMock('Asar\Http\RequestHandlerInterface');
        $this->router->expects($this->once())
            ->method('route')
            ->will($this->returnValue($resource));
        $resource->expects($this->once())
            ->method('handleRequest')
            ->with($request);
        $this->app->handleRequest($request);
    }

    /**
     * Returns response from resource found by router
     */
    public function testRetursResponseFromResourceFoundByRouter()
    {
        $resource = $this->quickMock('Asar\Http\RequestHandlerInterface');
        $this->router->expects($this->once())
            ->method('route')
            ->will($this->returnValue($resource));
        $resource->expects($this->once())
            ->method('handleRequest')
            ->will($this->returnValue($response = new Response));
        $this->assertEquals(
            $response, $this->app->handleRequest(new Request)
        );
    }

    /**
     * Returns 404 response status when router does not find resource
     */
    public function testReturns404StatusForUnknownResource()
    {
        $response = $this->app->handleRequest(new Request);
        $this->assertEquals(404, $response->getStatus());
    }


}