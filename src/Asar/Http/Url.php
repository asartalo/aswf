<?php
namespace Asar\Http;

/**
 * An object representation of a url
 *
 * The url object is immutable.
 */
class Url {

  private
    $scheme,
    $user,
    $pass,
    $host,
    $path,
    $port,
    $query,
    $fragment,
    $urlString,

    $parts = array(
      'scheme'   => 'http',
      'user'     => '',
      'pass'     => '',
      'host'     => '',
      'path'     => '',
      'port'     => '',
      'query'    => '',
      'fragment' => '',
    ),

    $setters = array(
      'port' => 'setPort'
    ),

    $defaultPorts = array(
      'http' => 80,
      'https' => 443
    );

  function __construct($data) {
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

  private function setUrlParts(array $parsedUrl) {
    foreach ($this->parts as $part => $default) {
      $value = isset($parsedUrl[$part]) ? $parsedUrl[$part] : $default;
      if (isset($this->setters[$part])) {
        $this->{$this->setters[$part]}($value);
      } else {
        $this->{$part} = $value;
      }
    }
  }

  private function createQueryString($query) {
    $queryJoined = array();
    foreach ($query as $key => $value) {
      $queryJoined[] = sprintf('%s=%s', urlencode($key), urlencode($value));
    }
    return implode('&', $queryJoined);
  }

  private function setPort($port) {
    if (!$port && isset($this->defaultPorts[$this->getScheme()])) {
      $this->port = $this->defaultPorts[$this->getScheme()];
    } else {
      $this->port = $port;
    }
  }

  private function constructUrlString() {
    return
      $this->getScheme() . '://' .
      $this->addUsernamePassword() .
      $this->getHost() .
      $this->addPort() .
      $this->getPath() .
      $this->addParamString() .
      $this->addFragment();
  }

  private function addUsernamePassword() {
    if ($this->user) {
      return "{$this->user}:{$this->pass}@";
    }
  }

  private function addPort() {
    if ($this->getPort() != 80) {
      return ":{$this->getPort()}";
    }
  }

  private function addParamString() {
    if ($this->query) {
      return "?{$this->getParamString()}";
    }
  }

  private function addFragment() {
    if ($this->fragment) {
      return "#{$this->fragment}";
    }
  }

  function getScheme() {
    return $this->scheme;
  }

  function getUsername() {
    return $this->user;
  }

  function getPassword() {
    return $this->pass;
  }

  function getHost() {
    return $this->host;
  }

  function getPort() {
    return $this->port;
  }

  function getPath() {
    return $this->path;
  }

  function getQuery() {
    return $this->getParameters();
  }

  function getQueryString() {
    return $this->getParamString();
  }

  function getParameters() {
    parse_str($this->query, $query);
    return $query;
  }

  function getParamString() {
    return $this->query;
  }

  function getFragment() {
    return $this->fragment;
  }

  function __toString() {
    return $this->urlString;
  }

}