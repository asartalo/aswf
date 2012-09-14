<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Content;

use Asar\Http\Message\Response;

/**
 * An object representation of a web page
 */
class Page
{
    private $contentParams = array();

    private $response;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->response = new Response;
    }

    /**
     * Gives a response
     *
     * @return Response
     */
    public function getResponse()
    {
        $contents = implode(' ', $this->contentParams);
        $this->response->setContent($contents);
        $this->response->setHeader('content-type', 'text/html; charset=utf-8');

        return $this->response;
    }

    /**
     * Sets a response header
     *
     * @param string $key   the response header key
     * @param string $value the response header value
     */
    public function setHeader($key, $value)
    {
        $this->response->setHeader($key, $value);
    }

    /**
     * Sets a content parameter
     *
     * @param string $key   the content parameter key
     * @param string $value the content parameter value
     */
    public function set($key, $value)
    {
        $this->contentParams[$key] = $value;
    }

}