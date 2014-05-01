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
use Asar\Http\Message\Request;
use Asar\Routing\Route;
use Asar\Template\TemplateAssembler;

/**
 * An object representation of a web page
 */
class Page
{

    private $assembler;

    private $route;

    private $request;

    private $response;

    private $contents = array();

    /**
     * Constructor
     *
     * @param TemplateAssembler $assembler the template assembler
     * @param Route             $route     the request route
     * @param Request           $request   the request
     */
    public function __construct(TemplateAssembler $assembler, Route $route, Request $request)
    {
        $this->assembler = $assembler;
        $this->route = $route;
        $this->request = $request;
        $this->response = new Response;
    }

    /**
     * Gives a response
     *
     * @return Response
     */
    public function getResponse()
    {
        $content = '';
        if ($template = $this->getTemplate()) {
            $content = $template->render($this->contents);
        }
        $this->response->setContent($content);
        $this->response->setHeader('content-type', 'text/html; charset=utf-8');

        return $this->response;
    }

    /**
     * Retrieves the template used
     *
     * @return Asar\Template\TemplateAssembly
     */
    public function getTemplate()
    {
        return $this->assembler->find(
            $this->route->getName(),
            array(
                'type' => 'html',
                'method' => $this->request->getMethod(),
                'status' => $this->response->getStatus()
            )
        );
    }

    /**
     * Sets a response header
     *
     * @param string $key   the response header key
     * @param mixed  $value the response header value
     */
    public function setHeader($key, $value)
    {
        $this->response->setHeader($key, $value);
    }

    /**
     * Sets a response status
     *
     * @param integer $statusCode the response status code
     */
    public function setStatus($statusCode)
    {
        $this->response->setStatus($statusCode);
    }

    /**
     * Sets a content parameter
     *
     * @param mixed  $var   the content parameter key or an associative
     *                      array of parameter keys and values
     * @param string $value the content parameter value
     */
    public function set($var, $value = null)
    {
        if (is_array($var)) {
            foreach ($var as $key => $value) {
                $this->contents[$key] = $value;
            }
        } else {
            $this->contents[$var] = $value;
        }
    }

}
