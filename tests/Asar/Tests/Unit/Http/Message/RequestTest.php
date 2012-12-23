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
use Asar\Http\Message\Request;
use Asar\Utilities\String;

/**
 * Specification for Asar\Http\Message\Request
 */
class RequestTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->request = new Request;
    }

    /**
     * Should be able to set path
     */
    public function testRequestShouldBeAbleToSetPath()
    {
        $this->request->setPath('/path/to/page');
        $this->assertEquals(
            '/path/to/page', $this->request->getPath(),
            'Unable to set path on Request object'
        );
    }

    /**
     * Defaults to index/root path
     */
    public function testRequestDefaultsToIndexPath()
    {
        $this->assertEquals(
            '/', $this->request->getPath(),
            'Path does not default to index ("/").'
        );
    }

    /**
     * Should be able to set method
     */
    public function testRequestShouldBeAbleToSetMethod()
    {
        $this->request->setMethod('POST');
        $this->assertEquals(
            'POST', $this->request->getMethod(),
            'Unable to set method on Request object'
        );
    }

    /**
     * Defaults to GET method on initialization
     */
    public function testRequestShouldDefaultToGetMethodOnInitialization()
    {
        $this->assertEquals(
            'GET', $this->request->getMethod(),
            'Method does not default to GET on Initialization'
        );
    }

    /**
     * Can set request parameters
     */
    public function testSettingRequestParameters()
    {
        $this->request->setParams(array('foo' => 'bar', 'fruit' => 'apple'));
        $params = $this->request->getParams();
        $this->assertEquals(
            'bar', $params['foo'],
            'Foo param in request params not found'
        );
        $this->assertEquals(
            'apple', $params['fruit'],
            'Fruit param in request params not found'
        );
    }

    /**
     * Should default to HTML for content-type
     */
    public function testRequestShouldDefaultToHtmlContentType()
    {
        $this->assertEquals(
            'text/html', $this->request->getHeader('Accept'),
            'Content-type does not default to "text/html" on initialization.'
        );
    }

    /**
     * Can set content on creation
     */
    public function testRequestSettingContentOnCreation()
    {
        $request = new Request(array('content' => 'foo bar'));
        $this->assertEquals('foo bar', $request->getContent());
    }

    /**
     * Can set path on creation
     */
    public function testRequestSettingPathOnCreation()
    {
        $request = new Request(array('path' => '/foo'));
        $this->assertEquals('/foo', $request->getPath());
    }

    /**
     * Can set request method on creation
     */
    public function testRequestSettingMethodOnCreation()
    {
        $request = new Request(array('method' => 'PUT'));
        $this->assertEquals('PUT', $request->getMethod());
    }

    /**
     * Can set multiple properties on creation
     */
    public function testSettingMultiplePropertiesOnCreation()
    {
        $request = new Request(array(
            'method' => 'POST',
            'content' => 'churva',
            'headers' => array('foo' => 'bar')
        ));
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('churva', $request->getContent());
        $this->assertEquals('bar', $request->getHeader('foo'));
    }

    /**
     * Can set request parameters on creation
     */
    public function testSettingParamsCreation()
    {
        $request = new Request(array(
            'params' => array('foo' => 'bar')
        ));
        $this->assertEquals(array('foo' => 'bar'), $request->getParams());
    }

    /**
     * Can get a single parameter
     */
    public function testGettingASingleParameter()
    {
        $request = new Request(array(
            'params' => array('foo' => 'bar')
        ));
        $this->assertEquals('bar', $request->getParam('foo'));
    }

    /**
     * A request with URI set
     *
     * @return Request a request constructed with just URI data
     */
    public function topicSettingUri()
    {
        return new Request(array(
            'uri' => 'http://some.domain.on/somewhere/out/there?with=params&other=1'
        ));
    }

    /**
     * Able to obtain URI from request set with URI
     */
    public function testSettingUri()
    {
        $this->assertEquals(
            'http://some.domain.on/somewhere/out/there?with=params&other=1',
            $this->topicSettingUri()->getUri()
        );
    }

    /**
     * Able to set path from request set with URI
     */
    public function testSettingUriSetsPath()
    {
        $this->assertEquals(
            '/somewhere/out/there', $this->topicSettingUri()->getPath()
        );
    }

    /**
     * Getting a single parameter returns null when undefined
     */
    public function testGettingASingleParameterDefaultsToNullForUndefinedValues()
    {
        $request = new Request;
        $this->assertSame(null, $request->getParam('foo'));
    }

    /**
     * HTTP request string test topic
     *
     * @return Request a regular request object
     */
    protected function topicExportRawHttpRequestString()
    {
        $request = new Request(array(
            'path'    => '/a/path/to/a/resource.html',
            'headers' => array('Accept' => 'text/html', 'Connection' => 'Close' )
        ));

        return $request->export();
    }

    /**
     * Exported HTTP request string has a proper request line
     */
    public function testExportRawHttpRequestHasCorrectRequestLine()
    {
        $this->assertStringStartsWith(
            "GET /a/path/to/a/resource.html HTTP/1.1\r\n",
            $this->topicExportRawHttpRequestString(),
            'Did not find request line in generated Raw HTTP Request string.'
        );
    }

    /**
     * Exported HTTP request string has proper suffix
     */
    public function testExportRawHttpRequestStringEndsCorrectly()
    {
        $this->assertStringEndsWith(
            "\r\n\r\n", $this->topicExportRawHttpRequestString(),
            'Raw HTTP Request string should end in "\r\n\r\n".'
        );
    }

    /**
     * Exported HTTP request string sets correct headers
     */
    public function testExportRawHttpRequestStringSetsCorrectHeaders()
    {
        $this->_testHeaders(
            array('Accept' => 'text/html', 'Connection' => 'Close' ),
            $this->topicExportRawHttpRequestString()
        );
    }

    /**
     * Creates a string formatted as HTTP header key used in regular expression
     *
     * @param string $key A string to convert
     *
     * @return string a matching header key
     */
    private function createMatchingHeaderKey($key)
    {
        return str_replace(
            array('.', '-'), array('\.', '\-'), String::dashCamelCase($key)
        );
    }

    /**
     * Creates a string suitable for regular expression
     *
     * @param string $value A string to convert
     *
     * @return string a matching header value
     */
    private function createMatchingHeaderValue($value)
    {
        return str_replace('/', '\/', $value);
    }

    /**
     * Test wether headers are found on HTTP Message string and formatted correctly
     *
     * @param array  $headers    Header-key value pairs
     * @param string $messageStr The HTTP Message string to test against
     */
    private function _testHeaders($headers, $messageStr)
    {
        foreach ($headers as $key => $value) {
            $this->assertRegExp(
                sprintf(
                    "/\r\n%s: %s\r\n/",
                    $this->createMatchingHeaderKey($key), $this->createMatchingHeaderValue($value)
                ),
                $messageStr,
                "Did not find the $key header that was set."
            );
        }
    }

    private function topicExportWithPostValues()
    {
        $request = new Request(array(
            'method'  => 'POST',
            'path'    => '/post/processor',
            'content' => array(
                'foo' => 'bar', 'goo[]' => 'jazz', 'good' => 'bad='
            )
        ));

        return $request->export();
    }

    /**
     * Export with post values sets correct request line
     */
    public function testExportWithPostValuesSetsCorrectRequestLine()
    {
        $this->assertStringStartsWith(
            "POST /post/processor HTTP/1.1\r\n",
            $this->topicExportWithPostValues(),
            'Incorrect request line in generated Raw HTTP Request string.'
        );
    }

    /**
     * Export with POST values sets correct headers
     */
    public function testExportWithPostValuesSetsCorrectHeaders()
    {
        $content = 'foo=bar&goo%5B%5D=jazz&good=bad%3D';
        $headers = array(
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Content-Length' => strlen($content)
        );
        $this->_testHeaders($headers, $this->topicExportWithPostValues());
    }

    /**
     * Export with POST values sets correct content
     */
    public function testExportWithPostValuesSetsCorrectContent()
    {
        $content = 'foo=bar&goo%5B%5D=jazz&good=bad%3D';
        $this->assertStringEndsWith(
            "\r\n\r\n$content", $this->topicExportWithPostValues(),
            'Raw HTTP Request string should end in "\r\n\r\n' . $content . '".'
        );
    }

    private function topicExportGet()
    {
        $request = new Request(array(
            'path'    => '/a/get/path',
            'content' => array('foo' => 'bar')
        ));

        return $request->export();
    }

    /**
     * Exported GET request should have no POST-like headers
     */
    public function testExportGetShouldHaveNoPostLikeHeader()
    {
        $str = $this->topicExportGet();
        $notExpected = 'foo=bar';
        $this->assertNotContains(
            "\r\nContent-Type: application/x-www-form-urlencoded\r\n", $str
        );
        $this->assertNotContains(
            "\r\nContent-Length: " . strlen($notExpected) . "\r\n", $str
        );
    }

    /**
     * Expored GET should have no content
     */
    public function testExportGetShouldHaveNoContent()
    {
        $this->assertNotContains("foo=bar\r\n\r\n", $this->topicExportGet());
    }

    /**
     * Exported request with parameters in URL encodes parameter values
     */
    public function testExportRequestWithParamsUrlEncodesParamValues()
    {
        $request = new Request(array(
            'path'    => '/handler',
            'params' => array(
                'foo' => 'bar', 'goo[]' => 'jazz', 'good' => 'bad='
            )
        ));
        $expected = 'foo=bar&' . urlencode('goo[]') . '=jazz&good=bad' .
            urlencode('=');
        $str = $request->export();
        $this->assertStringStartsWith(
            "GET /handler?$expected HTTP/1.1\r\n", $str, $str
        );
    }

}
