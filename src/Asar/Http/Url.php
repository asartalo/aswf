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
 * An object representation of a url
 *
 * The url object is immutable.
 */
class Url
{
    private $scheme;

    private $user;
    private $pass;
    private $host;
    private $path;
    private $port;
    private $query;
    private $fragment;
    private $urlString;

    private $parts = array(
        'scheme'   => 'http',
        'user'     => '',
        'pass'     => '',
        'host'     => '',
        'path'     => '',
        'port'     => '',
        'query'    => '',
        'fragment' => '',
    );

    private $setters = array(
        'port' => 'setPort'
    );

    private $defaultPorts = array(
        'http' => 80,
        'https' => 443
    );

    /**
     * @param mixed $data a URI string or an associative array of URI parts
     */
    public function __construct($data)
    {
        if (is_string($data)) {
            $this->setUrlParts(parse_url($data));
            $this->urlString = $data;
        } elseif (is_array($data)) {
            if (isset($data['username'])) {
                $data['user']  = $data['username'];
            }
            if (isset($data['password'])) {
                $data['pass']  = $data['password'];
            }
            if (isset($data['query'])) {
                $data['query'] = $this->createQueryString($data['query']);
            }
            $this->setUrlParts($data);
            $this->urlString = $this->constructUrlString();
        }
    }

    private function setUrlParts(array $parsedUrl)
    {
        foreach ($this->parts as $part => $default) {
            $value = isset($parsedUrl[$part]) ? $parsedUrl[$part] : $default;
            if (isset($this->setters[$part])) {
                $this->{$this->setters[$part]}($value);
            } else {
                $this->{$part} = $value;
            }
        }
    }

    private function createQueryString($query)
    {
        $queryJoined = array();
        foreach ($query as $key => $value) {
            $queryJoined[] = sprintf('%s=%s', urlencode($key), urlencode($value));
        }

        return implode('&', $queryJoined);
    }

    private function setPort($port)
    {
        if (!$port && isset($this->defaultPorts[$this->getScheme()])) {
            $this->port = $this->defaultPorts[$this->getScheme()];
        } else {
            $this->port = $port;
        }
    }

    private function constructUrlString()
    {
        return
            $this->getScheme() . '://' .
            $this->addUsernamePassword() .
            $this->getHost() .
            $this->addPort() .
            $this->getPath() .
            $this->addParamString() .
            $this->addFragment();
    }

    private function addUsernamePassword()
    {
        if ($this->user) {
            return "{$this->user}:{$this->pass}@";
        }
    }

    private function addPort()
    {
        if ($this->getPort() != 80) {
            return ":{$this->getPort()}";
        }
    }

    private function addParamString()
    {
        if ($this->query) {
            return "?{$this->getParamString()}";
        }
    }

    private function addFragment()
    {
        if ($this->fragment) {
            return "#{$this->fragment}";
        }
    }

    /**
     * @return string the URI scheme (e.g. http, ftp)
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return string the username if specified
     */
    public function getUsername()
    {
        return $this->user;
    }

    /**
     * @return string the password if specified
     */
    public function getPassword()
    {
        return $this->pass;
    }

    /**
     * @return string the host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return integer the port number
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string the webroot-relative path of the URI
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * An alias of getParameters()
     *
     * @return array an associative array of request parameters
     */
    public function getQuery()
    {
        return $this->getParameters();
    }

    /**
     * An alias of getParamString()
     *
     * @return string the request parameters in string form
     */
    public function getQueryString()
    {
        return $this->getParamString();
    }

    /**
     * @return array an associative array of request parameters
     */
    public function getParameters()
    {
        parse_str($this->query, $query);

        return $query;
    }

    /**
     * @return string the request parameters in string form
     */
    public function getParamString()
    {
        return $this->query;
    }

    /**
     * Returns the fragment part of the URL (e.g. #bookmark) minus the hash
     *
     * @return string the URL fragment
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @return string the URL in string form
     */
    public function __toString()
    {
        return $this->urlString;
    }

}
