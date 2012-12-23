<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Http\Message;

use Asar\TestHelper\TestCase;
use Asar\Http\Message\Response;

/**
 * Specifications for Asar\Http\Message\Response
 */
class ResponseTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->response = new Response;
    }

    /**
     * Able to set status code
     */
    public function testAbleToSetStatus()
    {
        $this->response->setStatus(404);
        $this->assertEquals(
            404, $this->response->getStatus(),
            'Unable to set Status'
        );
    }

    /**
     * Can get reason phrases based on status code
     *
     * @param integer $status       the response status code
     * @param string  $reasonPhrase the expected reason phrase
     *
     * @dataProvider dataStatusCodeMessages
     */
    public function testGettingReasonPhrases($status, $reasonPhrase)
    {
        $this->assertEquals(
            $reasonPhrase, Response::getReasonPhrase($status)
        );
    }

    /**
     * Status code reason phrases
     *
     * @return array test data for
     */
    public function dataStatusCodeMessages()
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
     * Can obtain reason phrase from status summary
     *
     * @param int    $status       status codes
     * @param string $reasonPhrase expected reason phrase
     *
     * @dataProvider dataStatusCodeMessages
     */
    public function testGetStatusMessagesFromStatusSummary($status, $reasonPhrase)
    {
        $response = new Response(array('status' => $status));
        $this->assertEquals($reasonPhrase, $response->getStatusReasonPhrase());
    }

    /**
     * Can set status on creation
     */
    public function testSettingStatusOnCreation()
    {
        $response = new Response(array('status' => 404));
        $this->assertEquals(404, $response->getStatus());
    }

    /**
     * Default status code is 200
     */
    public function testStatusDefaultsTo200OnCreation()
    {
        $response = new Response();
        $this->assertEquals(200, $response->getStatus());
    }

    /**
     * Can import data from raw HTTP Response string
     *
     * @param array $params creation options
     */
    public function testImportingRawHttpResponseString($params = array())
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
        $response = new Response;
        $response->import($raw);
        $this->assertEquals($status, $response->getStatus());
        $headers = $response->getHeaders();
        $this->assertEquals('text/plain', $headers['Content-Type']);
        $this->assertEquals($clength, $headers['Content-Length']);
        $this->assertEquals('Accept-Encoding', $headers['Vary']);
        $this->assertEquals('Apache/2.2.11', $headers['Server']);
        $this->assertEquals($content, $response->getContent());
    }

    /**
     * Imports raw HTTP Response string containing crlf sequence
     */
    public function testImportRawHttpResponseWithContentContainingCrlfSequence()
    {
        $params = array(
            'content' => "A Very Dangerous\r\nContent.\r\n\r\nReally."
        );
        $this->testImportingRawHttpResponseString($params);
    }

    /**
     * Can import raw HTTP Response string with a different status code
     */
    public function testImportRawHttpResponseWithADifferentStatusCode()
    {
        $this->testImportingRawHttpResponseString(array('status' => 201));
    }
}
