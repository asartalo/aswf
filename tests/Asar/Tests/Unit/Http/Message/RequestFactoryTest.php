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
use Asar\Http\Message\RequestFactory;

/**
 * Specification for Asar\Http\Message\RequestFactory
 */
class RequestFactoryTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->factory = new RequestFactory;
    }

    /**
     * Creates requests from server variables
     */
    public function testCreatingRequest()
    {
        $server = array();
        $server['REQUEST_METHOD'] = 'POST';
        $server['REQUEST_URI']  = '/a_page';
        $request = $this->factory->createRequestFromEnvironment($server);
        $this->assertInstanceOf('Asar\Http\Message\Request', $request);
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/a_page', $request->getPath());
    }

    /**
     * Creates requests with params
     */
    public function testCreatingRequestWithParams()
    {
        $server = array();
        $server['REQUEST_METHOD'] = 'DELETE';
        $server['REQUEST_URI']  = '/foo?foo=bar&boo=far';

        $get = array(
            'foo' => 'bar',
            'boo' => 'far'
        );
        $request = $this->factory->createRequestFromEnvironment($server, $get);
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('/foo', $request->getPath());
        $this->assertEquals($get, $request->getParams());
    }

    /**
     * Creates request with POST variables
     */
    public function testCreatingRequestWithPostVars()
    {
        $server = array();
        $server['REQUEST_METHOD'] = 'POST';
        $server['REQUEST_URI']  = '/foo';
        $post = array(
            'foo' => 'bar',
            'boo' => 'far'
        );
        $request = $this->factory->createRequestFromEnvironment($server, array(), $post);
        $this->assertEquals($post, $request->getContent());
    }

    /**
     * GET requests do not have content
     */
    public function testCreatingGetRequestDoesNotSetContent()
    {
        $server = array();
        $server['REQUEST_METHOD'] = 'GET';
        $server['REQUEST_URI']  = '/foo';
        $post = array(
            'foo' => 'bar',
            'boo' => 'far'
        );
        $request = $this->factory->createRequestFromEnvironment($server, array(), $post);
        $this->assertEquals(null, $request->getContent());
    }

    /**
     * Creates proper headers
     */
    public function testCreatingRequestSetsHeaders()
    {
        $server = array(
            'REQUEST_METHOD'       => 'GET',
            "HTTP_HOST"            => 'localhost',
            "HTTP_USER_AGENT"      => 'Mozilla/5.0 (X11; U; Linux i686;)',
            "HTTP_ACCEPT"          => "text/html,application/xml;q=0.9,*/*;q=0.8",
            "HTTP_ACCEPT_LANGUAGE" => 'tl,en-us;q=>0.7,en;q=0.3',
            "HTTP_ACCEPT_ENCODING" => 'gzip,deflate',
            "HTTP_ACCEPT_CHARSET"  => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            "HTTP_KEEP_ALIVE"    => '300',
            "HTTP_CONNECTION"    => 'keep-alive',
            'REQUEST_URI'      => 'somewhere_over_the_rainbow'
        );
        $headers = $this->factory->createRequestFromEnvironment($server)->getHeaders();
        $this->assertEquals('localhost', $headers['Host']);
        $this->assertEquals('Mozilla/5.0 (X11; U; Linux i686;)', $headers['User-Agent']);
        $this->assertEquals("text/html,application/xml;q=0.9,*/*;q=0.8", $headers['Accept']);
        $this->assertEquals('tl,en-us;q=>0.7,en;q=0.3', $headers['Accept-Language']);
        $this->assertEquals('gzip,deflate', $headers['Accept-Encoding']);
        $this->assertEquals('ISO-8859-1,utf-8;q=0.7,*;q=0.7', $headers['Accept-Charset']);
        $this->assertEquals('300', $headers['Keep-Alive']);
        $this->assertEquals('keep-alive', $headers['Connection']);
    }

}