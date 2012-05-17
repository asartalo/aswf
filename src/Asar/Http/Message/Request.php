<?php
namespace Asar\Http\Message;

class Request extends Message {

  private
    $path    = '/',
    $method  = 'GET',
    $params  = array();
  protected
    $headers = array('Accept' => 'text/html'),
    $content;

  function __construct($options = array()) {
    parent::__construct($options);
    $this->setIfExists('uri', $options, 'setUri');
    $this->setIfExists('path', $options, 'setPath');
    $this->setIfExists('method', $options, 'setMethod');
    $this->setIfExists('params', $options, 'setParams');
  }

  function setUri($uri) {
    $this->uri = $uri;
    $parsed = parse_url($this->uri);
    $this->setPath($parsed['path']);
  }

  function getUri() {
    return $this->uri;
  }

  function setPath($path) {
    $this->path = $path;
  }

  function getPath() {
    return $this->path;
  }

  function setMethod($method) {
    $this->method = $method;
  }

  function getMethod() {
    return $this->method;
  }

  function setParams(array $params) {
    $this->params = $params;
  }

  function getParams() {
    return $this->params;
  }

  function getParam($key) {
    if (isset($this->params[$key])) {
      return $this->params[$key];
    }
  }

  function setContent($content) {
    $this->content = $content;
  }

  function getContent() {
    return $this->content;
  }

  function export() {
    $str = sprintf(
      "%s %s HTTP/1.1\r\n", $this->getMethod(),
      $this->getPath() . $this->getParamsEncoded()
    );
    $headers = $this->getHeaders();
    $msg_body = '';
    if ($this->getMethod() == 'POST') {
      $headers['Content-Type'] = 'application/x-www-form-urlencoded';
      $msg_body = $this->createParamStr($this->getContent());
      $headers['Content-Length'] = strlen($msg_body);
    }
    foreach ($headers as $key => $value) {
      $str .= $key . ': ' . $value . "\r\n";
    }
    return $str . "\r\n" . $msg_body;
  }

  private function getParamsEncoded() {
    if (count($this->params)) {
      return '?' . $this->createParamStr($this->params);
    }
  }

  private function createParamStr($params) {
    if (is_array($params)) {
      return http_build_query($params, '', '&');
    }
  }

}
