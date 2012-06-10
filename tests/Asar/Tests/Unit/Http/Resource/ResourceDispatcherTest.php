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

use Asar\Http\Resource\ResourceDispatcher;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;
use Asar\Tests\TestCase;

class ResourceDispatcherTest extends TestCase
{

    public function testDispatcherImplementsRequestHandler()
    {
        $this->assertInstanceOf('Asar\Http\RequestHandlerInterface', new ResourceDispatcher);
    }

    public function testHandlesRequest()
    {
        $dispatcher = new ResourceDispatcher;
        $this->assertInstanceOf(
            'Asar\Http\Message\Response', $dispatcher->handleRequest(new Request)
        );
    }
    public function testReturns404ResponseWhenThereIsNoResourcePassed()
    {
        $dispatcher = new ResourceDispatcher;
        $this->assertEquals(404, $dispatcher->handleRequest(new Request)->getStatus());
    }

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
        $dispatcher = new ResourceDispatcher($resource);
        $dispatcher->handleRequest($request);
    }

    /**
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
        $dispatcher = new ResourceDispatcher($resource);
        $this->assertSame($response, $dispatcher->handleRequest($request));
    }

    public function testReturnsStatus405WhenResourceDoesNotHaveMethod()
    {
        $resource = new \stdClass;
        $dispatcher = new ResourceDispatcher($resource);
        $this->assertEquals(405, $dispatcher->handleRequest(new Request)->getStatus());
    }

    public function testUsesGetMethodFromResourceWhenItsAHeadRequest()
    {
        $request = new Request(array('method' => 'HEAD'));
        $resource = $this->quickMock('Asar\Http\Resource\GetInterface');
        $resource->expects($this->once())
            ->method('GET')
            ->with($request)
            ->will($this->returnValue(new Response(array('content' => 'foo'))));
        $dispatcher = new ResourceDispatcher($resource);
        $dispatcher->handleRequest($request);
    }

    public function testResponseFromResourceWhenHeadRequestMustHaveNoContent()
    {
        $request = new Request(array('method' => 'HEAD'));
        $resource = $this->quickMock('Asar\Http\Resource\GetInterface');
        $resource->expects($this->once())
            ->method('GET')
            ->with($request)
            ->will($this->returnValue(new Response(array('content' => 'foo'))));
        $dispatcher = new ResourceDispatcher($resource);
        $this->assertEquals('', $dispatcher->handleRequest($request)->getContent());
    }

}
