<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar;

use Asar\Http\RequestHandlerInterface as RequestHandler;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;

/**
 * A helper for interacting with Asar applications
 */
class Client
{

    /**
     * Sends an GET request to an application or request handler
     *
     * @param RequestHandler $app  an application or any request handler
     * @param string         $path the path to a resource
     *
     * @return Response
     */
    public function get(RequestHandler $app, $path)
    {
        return $app->handleRequest(new Request(array('path' => $path)));
    }

}