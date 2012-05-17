<?php

namespace Asar\Http\Resource;

use \Asar\Http\Message\Request;
use \Asar\Http\Message\Response;
use \Asar\Http\RequestHandlerInterface;
use \Asar\Utilities\String;

class ResourceDispatcher implements RequestHandlerInterface {

  private $resource;

  private $knownMethodInterfaces = array(
    'GET'    => 'Asar\Http\Resource\GetInterface',
    'POST'   => 'Asar\Http\Resource\PostInterface',
    'PUT'    => 'Asar\Http\Resource\PutInterface',
    'DELETE' => 'Asar\Http\Resource\DeleteInterface',
  );

  private $specialMethods = array(
    'HEAD' => 'invokeHeadRequest'
  );

  function __construct($resource = null) {
    $this->resource = $resource;
  }

  function handleRequest(Request $request) {
    if ($this->resource) {
      return $this->callResourceMethod($request, $request->getMethod());
    }
    $response = new Response(array('status' => 404));
    return $response;
  }

  protected function callResourceMethod($request, $method) {
    if (
      isset($this->knownMethodInterfaces[$method])
      && $this->resource instanceof $this->knownMethodInterfaces[$method]
    ) {
      return $this->resource->$method($request);
    }
    if (isset($this->specialMethods[$method])) {
      return call_user_func(array($this, $this->specialMethods[$method]), $request);
    }
    return new Response(array('status' => 405));
  }

  protected function invokeHeadRequest($request) {
    $response = $this->callResourceMethod($request, 'GET');
    $response->setContent('');
    return $response;
  }

}
