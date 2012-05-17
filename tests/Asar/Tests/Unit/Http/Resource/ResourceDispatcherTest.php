<?php

namespace Asar\Tests\Unit\Http\Resource;

use \Asar\Http\Resource\ResourceDispatcher;
use \Asar\Http\Message\Request;
use \Asar\Http\Message\Response;

class ResourceDispatcherTest extends \Asar\Tests\TestCase {

  function testDispatcherImplementsRequestHandler() {
    $this->assertInstanceOf('Asar\Http\RequestHandlerInterface', new ResourceDispatcher);
  }

  function testHandlesRequest() {
    $dispatcher = new ResourceDispatcher;
    $this->assertInstanceOf(
      'Asar\Http\Message\Response', $dispatcher->handleRequest(new Request)
    );
  }
  function testReturns404ResponseWhenThereIsNoResourcePassed() {
    $dispatcher = new ResourceDispatcher;
    $this->assertEquals(404, $dispatcher->handleRequest(new Request)->getStatus());
  }

  function requestMethods() {
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
  function testDelegatesToResourceMthodWhenPassedARequest($method) {
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
  function testUsesResponseFromResource($method) {
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

  function testReturnsStatus405WhenResourceDoesNotHaveMethod() {
    $resource = new \stdClass;
    $dispatcher = new ResourceDispatcher($resource);
    $this->assertEquals(405, $dispatcher->handleRequest(new Request)->getStatus());
  }

  function testUsesGetMethodFromResourceWhenItsAHeadRequest() {
    $request = new Request(array('method' => 'HEAD'));
    $resource = $this->quickMock('Asar\Http\Resource\GetInterface');
    $resource->expects($this->once())
      ->method('GET')
      ->with($request)
      ->will($this->returnValue(new Response(array('content' => 'foo'))));
    $dispatcher = new ResourceDispatcher($resource);
    $dispatcher->handleRequest($request);
  }

  function testResponseFromResourceWhenHeadRequestMustHaveNoContent() {
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
