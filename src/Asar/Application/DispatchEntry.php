<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Application;

use Asar\Http\Message\Request;
use Asar\Http\Message\Response;
use Dimple\Container;

/**
 * A helper for dispatching requests
 */
class DispatchEntry
{

    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container the DI container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Dispatches request
     *
     * @param Request $request the request
     *
     * @return Response the response
     */
    public function dispatch(Request $request)
    {
        $this->container->enterScope('request');
        $this->container['request.request'] = $request;
        $response = $this->container->get('request.response');
        $this->container->leaveScope('request');

        return $response;
    }

}