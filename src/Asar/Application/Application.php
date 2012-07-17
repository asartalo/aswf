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

use Asar\Http\RequestHandlerInterface as RequestHandler;
use Asar\Routing\RouterInterface;
use Asar\Http\Resource\Dispatcher;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;

/**
 * A web application
 */
class Application implements RequestHandler
{

    private $router;


    /**
     * @param Router $router a request router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request $request A request object
     *
     * @return Response A response object
     */
    public function handleRequest(Request $request)
    {
        if ($dispatcher = $this->router->route($request->getPath())) {
            $response = $dispatcher->handleRequest($request);
        } else {
            $response = new Response(array('status' => 404));
        }

        return $response;
    }

}