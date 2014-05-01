<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Http\Resource;

use Asar\TestHelper\TestCase;
use Asar\Http\Resource\Dispatcher;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;

/**
 * Specification for Asar\Http\Resource\Dispatcher
 */
class DispatcherTest extends TestCase
{

    /**
     * Can obtain resource
     */
    public function testCanObtainResource()
    {
        $resource = new \stdClass;
        $dispatcher = new Dispatcher($resource);
        $this->assertSame($resource, $dispatcher->getResource());
    }

    /**
     * Dispatcher is a RequestHandler
     */
    public function testDispatcherImplementsRequestHandler()
    {
        $this->assertInstanceOf(
            'Asar\Http\RequestHandlerInterface', new Dispatcher
        );
    }

    /**
     * Dispatcher can handle requests
     */
    public function testHandlesRequest()
    {
        $dispatcher = new Dispatcher;
        $this->assertInstanceOf(
            'Asar\Http\Message\Response',
            $dispatcher->handleRequest(new Request)
        );
    }

    /**
     * Available request methods
     *
     * These are common HTTP methods and are used in the tests.
     *
     * @return array
     */
    public function requestMethods()
    {
        return array(
            array('GET'),
            array('POST'),
            array('PUT'),
            array('DELETE')
        );
    }

    /**
     * Delegates to resource method when handling requests
     *
     * @param string $method
     *
     * @dataProvider requestMethods
     */
    public function testDelegatesToResourceMthodWhenPassedARequest($method)
    {
        $request = new Request;
        $request->setMethod($method);
        $methodCapitalized = ucfirst(strtolower($method));
        $resource = $this->quickMock("Asar\Http\Resource\\{$methodCapitalized}Interface");
        $resource->expects($this->once())
            ->method($method)
            ->with($request);
        $dispatcher = new Dispatcher($resource);
        $dispatcher->handleRequest($request);
    }

    /**
     * Uses response from resource
     *
     * @param string $method
     *
     * @dataProvider requestMethods
     */
    public function testUsesResponseFromResource($method)
    {
        $request = new Request;
        $response = new Response;
        $request->setMethod($method);
        $methodCapitalized = ucfirst(strtolower($method));
        $resource = $this->quickMock("Asar\Http\Resource\\{$methodCapitalized}Interface");
        $resource->expects($this->once())
            ->method($method)
            ->will($this->returnValue($response));
        $dispatcher = new Dispatcher($resource);
        $this->assertSame($response, $dispatcher->handleRequest($request));
    }

    /**
     * Returns 405 status when resource does not implement method
     */
    public function testReturnsStatus405WhenResourceDoesNotHaveMethod()
    {
        $resource = new \stdClass;
        $dispatcher = new Dispatcher($resource);
        $this->assertEquals(405, $dispatcher->handleRequest(new Request)->getStatus());
    }

    /**
     * Uses GET method for HEAD requests
     */
    public function testUsesGetMethodFromResourceWhenItsAHeadRequest()
    {
        $request = new Request(array('method' => 'HEAD'));
        $resource = $this->quickMock('Asar\Http\Resource\GetInterface');
        $resource->expects($this->once())
            ->method('GET')
            ->with($request)
            ->will($this->returnValue(new Response(array('content' => 'foo'))));
        $dispatcher = new Dispatcher($resource);
        $dispatcher->handleRequest($request);
    }

    /**
     * Removes content of GET method return for HEAD requests
     */
    public function testResponseFromResourceWhenHeadRequestMustHaveNoContent()
    {
        $request = new Request(array('method' => 'HEAD'));
        $resource = $this->quickMock('Asar\Http\Resource\GetInterface');
        $resource->expects($this->once())
            ->method('GET')
            ->with($request)
            ->will($this->returnValue(new Response(array('content' => 'foo'))));
        $dispatcher = new Dispatcher($resource);
        $this->assertEquals('', $dispatcher->handleRequest($request)->getContent());
    }

}
