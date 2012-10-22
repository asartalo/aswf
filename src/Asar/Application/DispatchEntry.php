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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Scope;

/**
 * A helper for dispatching requests
 */
class DispatchEntry
{

    private $container;

    /**
     * Constructor
     */
    public function __construct(ContainerInterface $container)
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
        $this->container->set('request.request', $request, 'request');
        $response = $this->container->get('request.response');
        $this->container->leaveScope('request');

        return $response;
    }

}