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
use Asar\Http\Message\Request;

/**
 * A web application
 */
class Application implements RequestHandler
{

    private $helper;


    /**
     * @param RequestHelper $helper handles requests for the application
     */
    public function __construct(DispatchEntry $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param Request $request A request object
     *
     * @return Response A response object
     */
    public function handleRequest(Request $request)
    {
        return $this->helper->dispatch($request);
    }

}