<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Http\Message;

/**
 * An HTTP Request
 */
class Request extends Message
{
    private $path    = '/';

    private $method  = 'GET';

    private $params  = array();

    protected $headers = array('Accept' => 'text/html');

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->setIfExists('uri', $options, 'setUri');
        $this->setIfExists('path', $options, 'setPath');
        $this->setIfExists('method', $options, 'setMethod');
        $this->setIfExists('params', $options, 'setParams');
    }

    /**
     * Sets the URI of the request
     *
     * @param string $uri the URI
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        $parsed = parse_url($this->uri);
        $this->setPath($parsed['path']);
    }

    /**
     * Get the URI
     *
     * @return string the URI
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set the resource path
     *
     * @param string $path the resource path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the path
     *
     * @return string the resource path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the HTTP method for this request
     *
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Get the HTTP method for this request
     *
     * @return string $method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the request parameters (queries)
     *
     * @param array $params an associative array of parameters to set
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Get the parameters (queries)
     *
     * @return array the parameters
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get a parameter's value
     *
     * @param string $key the name of the parameter to obtain
     *
     * @return string the parameter's value
     */
    public function getParam($key)
    {
        if (isset($this->params[$key])) {
            return $this->params[$key];
        }
    }

    /**
     * Exports the request to a raw HTTP Request string
     *
     * @return string the request in string format
     */
    public function export()
    {
        $str = sprintf(
            "%s %s HTTP/1.1\r\n", $this->getMethod(),
            $this->getPath() . $this->getParamsEncoded()
        );
        $headers = $this->getHeaders();
        $msgBody = '';
        if ($this->getMethod() == 'POST') {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $msgBody = $this->createParamStr($this->getContent());
            $headers['Content-Length'] = strlen($msgBody);
        }
        foreach ($headers as $key => $value) {
            $str .= $key . ': ' . $value . "\r\n";
        }

        return $str . "\r\n" . $msgBody;
    }

    private function getParamsEncoded()
    {
        if (count($this->params)) {
            return '?' . $this->createParamStr($this->params);
        }
    }

    private function createParamStr($params)
    {
        if (is_array($params)) {
            return http_build_query($params, '', '&');
        }
    }

}
