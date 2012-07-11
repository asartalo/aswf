<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Http\Resource;

use \Asar\Http\Message\Request;
use \Asar\Http\Message\Response;
use \Asar\Http\RequestHandlerInterface;

/**
 * Dispatches a resource to respond to a request
 */
class Dispatcher implements RequestHandlerInterface
{
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

    /**
     * @param mixed $resource a resource
     */
    public function __construct($resource = null)
    {
        $this->resource = $resource;
    }

    /**
     * Returns resource
     *
     * @return mixed the resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param Request $request a request object
     *
     * @return Response a response object
     */
    public function handleRequest(Request $request)
    {
        if ($this->resource) {
            return $this->callResourceMethod($request, $request->getMethod());
        }
        $response = new Response(array('status' => 404));

        return $response;
    }

    protected function callResourceMethod($request, $method)
    {
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

    protected function invokeHeadRequest($request)
    {
        $response = $this->callResourceMethod($request, 'GET');
        $response->setContent('');

        return $response;
    }

}
