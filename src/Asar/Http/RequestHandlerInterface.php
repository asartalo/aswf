<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Http;

/**
 * Handles a Requests and returns a Response
 */
interface RequestHandlerInterface
{

    /**
     * @param Asar\Http\Request $request A request object
     *
     * @return Asar\Http\Response A response object
     */
    public function handleRequest(Message\Request $request);
}
