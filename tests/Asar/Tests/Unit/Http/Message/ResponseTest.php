<?php

namespace Asar\Tests\Unit\Http\Message;

use \Asar\Http\Message\Response;

class ResponseTest extends \Asar\Tests\TestCase {

  function setUp()
  {
    $this->R = new Response;
  }

  function testAbleToSetStatus()
  {
    $this->R->setStatus(404);
    $this->assertEquals(
      404, $this->R->getStatus(),
      'Unable to set Status'
    );
  }

  /**
   * @dataProvider dataStatusCodeMessages
   */
  function testGettingReasonPhrases($status, $reason_phrase)
  {
    $this->assertEquals(
      $reason_phrase, Response::getReasonPhrase($status)
    );
  }

  function dataStatusCodeMessages()
  {
    return array(
      array(100, 'Continue'),
      array(101, 'Switching Protocols'),
      array(200, 'OK'),
      array(201, 'Created'),
      array(202, 'Accepted'),
      array(203, 'Non-Authoritative Information'),
      array(204, 'No Content'),
      array(205, 'Reset Content'),
      array(206, 'Partial Content'),
      array(300, 'Multiple Choices'),
      array(301, 'Moved Permanently'),
      array(302, 'Found'),
      array(303, 'See Other'),
      array(304, 'Not Modified'),
      array(305, 'Use Proxy'),
      array(307, 'Temporary Redirect'),
      array(400, 'Bad Request'),
      array(401, 'Unauthorized'),
      array(402, 'Payment Required'),
      array(403, 'Forbidden'),
      array(404, 'Not Found'),
      array(405, 'Method Not Allowed'),
      array(406, 'Not Acceptable'),
      array(407, 'Proxy Authentication Required'),
      array(408, 'Request Timeout'),
      array(409, 'Conflict'),
      array(410, 'Gone'),
      array(411, 'Length Required'),
      array(412, 'Precondition Failed'),
      array(413, 'Request Entity Too Large'),
      array(414, 'Request-URI Too Long'),
      array(415, 'Unsupported Media Type'),
      array(416, 'Requested Range Not Satisfiable'),
      array(417, 'Expectation Failed'),
      array(500, 'Internal Server Error'),
      array(501, 'Not Implemented'),
      array(502, 'Bad Gateway'),
      array(503, 'Service Unavailable'),

      // Edge cases...
      array('foo', null),
      array(null,  null),
      array(1,     null),
      array(600,   null),
      array(460,   null),
      array(10,    null),
    );
  }

  /**
   * @dataProvider dataStatusCodeMessages
   */
  function testGetStatusMessagesFromStatusSummary($status, $reason_phrase)
  {
    $R = new Response(array('status' => $status));
    $this->assertEquals($reason_phrase, $R->getStatusReasonPhrase());
  }

  function testSettingStatusOnCreation()
  {
    $R = new Response(array('status' => 404));
    $this->assertEquals(404, $R->getStatus());
  }

  function testStatusDefaultsTo200OnCreation()
  {
    $R = new Response();
    $this->assertEquals(200, $R->getStatus());
  }

  function testImportingRawHttpResponseString($params = array())
  {
    $defaults = array(
       'content' => 'Hello World',
       'status'  => 200
    );
    extract(array_merge($defaults, $params));
    $clength = strlen($content);
    $raw = "HTTP/1.1 $status OK\r\n".
      "Date: Sat, 14 Nov 2009 18:31:11 GMT\r\n" .
      "Server: Apache/2.2.11\r\n" .
      "Last-Modified: Sat, 14 Nov 2009 06:32:48 GMT\r\n" .
      "ETag: \"181fc-198-4784ef1e5e400\"\r\n" .
      "Accept-Ranges: bytes\r\n" .
      "Content-Length: $clength\r\n" .
      "Vary: Accept-Encoding\r\n" .
      "Connection: close\r\n" .
      "Content-Type: text/plain\r\n\r\n" .
      $content;
    $R = new Response;
    $R->import($raw);
    $this->assertEquals($status, $R->getStatus());
    $headers = $R->getHeaders();
    $this->assertEquals('text/plain', $headers['Content-Type']);
    $this->assertEquals($clength, $headers['Content-Length']);
    $this->assertEquals('Accept-Encoding', $headers['Vary']);
    $this->assertEquals('Apache/2.2.11', $headers['Server']);
    $this->assertEquals($content, $R->getContent());
  }

  function testImportRawHttpResponseWithContentContainingCrlfSequence()
  {
    $params = array(
      'content' => "A Very Dangerous\r\nContent.\r\n\r\nReally."
    );
    $this->testImportingRawHttpResponseString($params);
  }

  function testImportRawHttpResponseWithADifferentStatusCode()
  {
    $this->testImportingRawHttpResponseString(array('status' => 201));
  }
}
