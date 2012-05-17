<?php
namespace Asar\Http\Message;

class Response extends Message {

  private $status = 200;
  static $reason_phrases = array(
    100 => 'Continue',
    101 => 'Switching Protocols',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    307 => 'Temporary Redirect',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Requested Range Not Satisfiable',
    417 => 'Expectation Failed',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable'
  );

  function __construct($options = array()) {
    parent::__construct($options);
    $this->setIfExists('status', $options, 'setStatus');
  }

  function setStatus($status) {
    $this->status = $status;
  }

  function getStatus() {
    return $this->status;
  }

  function getStatusReasonPhrase() {
    return self::getReasonPhrase($this->getStatus());
  }

  static function getReasonPhrase($status) {
    if (!isset(self::$reason_phrases[$status])) {
      return null;
    }
    return self::$reason_phrases[$status];
  }

  function import($str) {
    $rawarr = explode("\r\n\r\n", $str, 2);
    $this->setContent(array_pop($rawarr));
    $headers = explode("\r\n", $rawarr[0]);
    $response_line = array_shift($headers);
    $this->setStatus(intval(str_replace('HTTP/1.1 ', '', $response_line)));
    foreach ($headers as $line) {
      $header = explode(':', $line, 2);
      $this->setHeader($header[0], ltrim($header[1]));
    }
  }

}
