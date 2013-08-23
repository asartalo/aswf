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
 * An abstraction of HTTP Messages
 */
abstract class Message
{
    protected $headers = array(), $content = '';

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->setIfExists('content', $options, 'setContent');
        $this->setIfExists('headers', $options, 'setHeaders');
    }

    /**
     * Calls a setter if an option exists
     *
     * @param string $key     the option key
     * @param array  $options the options
     * @param string $method  the method to call
     */
    protected function setIfExists($key, $options, $method)
    {
        if (array_key_exists($key, $options)) {
            call_user_func(array($this, $method), $options[$key]);
        }
    }

    /**
     * Sets a message header
     *
     * @param string $name  the header name
     * @param string $value the header value
     */
    public function setHeader($name, $value)
    {
        if ($value != null) {
            $this->headers[$this->dashCamelCase($name)] = $value;
        } else {
            if (array_key_exists($this->dashCamelCase($name), $this->headers)) {
                $this->unsetHeader($name);
            }
        }
    }

    /**
     * Unsets a message header
     *
     * @param string $name the name of the header to unset
     */
    public function unsetHeader($name)
    {
        unset($this->headers[$this->dashCamelCase($name)]);
    }

    /**
     * Get the value of a message header
     *
     * @param string $name the name of the message header
     *
     * @return string the value of the message header or null if it is not set
     */
    public function getHeader($name)
    {
        $key = $this->dashCamelCase($name);
        if (isset($this->headers[$key])) {
            return $this->headers[$key];
        }
    }

    /**
     * Sets multiple message headers at once
     *
     * @param array $headers an associative array of header name => value pairs
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
    }

    /**
     * Gets all message headers
     *
     * @return array $headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }


    /**
     * Sets the content
     *
     * @param string $content the message content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get the message content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Alias of getContent()
     *
     * @return string $content
     */
    public function getBody()
    {
        return $this->getContent();
    }

    private function dashCamelCase($string)
    {
        return str_replace(' ', '-', $this->ucwordsLower($string));
    }

    private function ucwordsLower($string)
    {
        return ucwords(
            strtolower(str_replace(array('-', '_'), ' ', $string))
        );
    }
}
