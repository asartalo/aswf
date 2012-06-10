<?php

namespace Asar\Tests\Unit\Http\Message;

use \Asar\Http\Message\Request;
use \Asar\Utilities\String;

class RequestTest extends \Asar\Tests\TestCase {

  function setUp()
  {
    $this->R = new Request;
  }

  function testRequestShouldBeAbleToSetPath()
  {
    $this->R->setPath('/path/to/page');
    $this->assertEquals(
      '/path/to/page', $this->R->getPath(),
      'Unable to set path on Request object'
    );
  }

  function testRequestDefaultsToIndexPath()
  {
    $this->assertEquals(
      '/', $this->R->getPath(),
      'Path does not default to index ("/").'
    );
  }

  function testRequestShouldBeAbleToSetMethod()
  {
    $this->R->setMethod('POST');
    $this->assertEquals(
      'POST', $this->R->getMethod(),
      'Unable to set method on Request object'
    );
  }

  function testRequestShouldDefaultToGetMethodOnInitialization()
  {
    $this->assertEquals(
      'GET', $this->R->getMethod(),
      'Method does not default to GET on Initialization'
    );
  }

  function testSettingRequestParameters()
  {
    $this->R->setParams(array('foo' => 'bar', 'fruit' => 'apple'));
    $params = $this->R->getParams();
    $this->assertEquals(
      'bar', $params['foo'],
      'Foo param in request params not found'
    );
    $this->assertEquals(
      'apple', $params['fruit'],
      'Fruit param in request params not found'
    );
  }

  function testRequestShouldDefaultToHtmlContentType()
  {
    $this->assertEquals(
      'text/html', $this->R->getHeader('Accept'),
      'Content-type does not default to "text/html" on initialization.'
    );
  }

  function testRequestSettingContentOnCreation()
  {
    $R = new Request(array('content' => 'foo bar'));
    $this->assertEquals('foo bar', $R->getContent());
  }

  function testRequestSettingPathOnCreation()
  {
    $R = new Request(array('path' => '/foo'));
    $this->assertEquals('/foo', $R->getPath());
  }

  function testRequestSettingMethodOnCreation()
  {
    $R = new Request(array('method' => 'PUT'));
    $this->assertEquals('PUT', $R->getMethod());
  }

  function testSettingMultiplePropertiesOnCreation()
  {
    $R = new Request(array(
      'method' => 'POST',
      'content' => 'churva',
      'headers' => array('foo' => 'bar')
    ));
    $this->assertEquals('POST',   $R->getMethod());
    $this->assertEquals('churva', $R->getContent());
    $this->assertEquals('bar',    $R->getHeader('foo'));
  }

  function testSettingParamsCreation()
  {
    $R = new Request(array(
      'params' => array('foo' => 'bar')
    ));
    $this->assertEquals(array('foo' => 'bar'), $R->getParams());
  }

  function testGettingASingleParameter()
  {
    $R = new Request(array(
      'params' => array('foo' => 'bar')
    ));
    $this->assertEquals('bar', $R->getParam('foo'));
  }

  function topicSettingUri()
  {
    return new Request(array(
      'uri' => 'http://some.domain.on/somewhere/out/there?with=params&other=1'
    ));
  }

  function testSettingUri()
  {
    $this->assertEquals(
      'http://some.domain.on/somewhere/out/there?with=params&other=1',
      $this->topicSettingUri()->getUri()
    );
  }

  function testSettingUriSetsPath()
  {
    $this->assertEquals(
      '/somewhere/out/there', $this->topicSettingUri()->getPath()
    );
  }

  function testGettingASingleParameterDefaultsToNullForUndefinedValues()
  {
    $R = new Request;
    $this->assertSame(null, $R->getParam('foo'));
  }

  protected function topicExportRawHttpRequestString()
  {
    $R = new Request(array(
      'path'    => '/a/path/to/a/resource.html',
      'headers' => array('Accept' => 'text/html', 'Connection' => 'Close' )
    ));

    return $R->export();
  }

  function testExportRawHttpRequestStringStartsCorrectly()
  {
    $this->assertStringStartsWith(
      "GET /a/path/to/a/resource.html HTTP/1.1\r\n",
      $this->topicExportRawHttpRequestString(),
      'Did not find request line in generated Raw HTTP Request string.'
    );
  }

  function testExportRawHttpRequestStringEndsCorrectly()
  {
    $this->assertStringEndsWith(
      "\r\n\r\n", $this->topicExportRawHttpRequestString(),
      'Raw HTTP Request string should end in "\r\n\r\n".'
    );
  }

  function testExportRawHttpRequestStringSetsCorrectHeaders()
  {
    $this->_testHeaders(
      array('Accept' => 'text/html', 'Connection' => 'Close' ),
      $this->topicExportRawHttpRequestString()
    );
  }

  /**
   * Creates a string formatted as HTTP header key used in regular expression
   * @param string $value A string to convert
   */
  private function createMatchingHeaderKey($key)
  {
    return str_replace(
      array('.', '-'), array('\.', '\-'), String::dashCamelCase($key)
    );
  }

  /**
   * Creates a string suitable for regular expression
   * @param string $value A string to convert
   */
  private function createMatchingHeaderValue($value)
  {
    return str_replace('/', '\/', $value);
  }

  /**
   * Test wether headers are found on HTTP Message string and formatted correctly
   * @param array  $headers Header-key value pairs
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
    $R = new Request(array(
      'method'  => 'POST',
      'path'    => '/post/processor',
      'content' => array(
        'foo' => 'bar', 'goo[]' => 'jazz', 'good' => 'bad='
      )
    ));

    return $R->export();
  }

  function testExportWithPostValuesSetsCorrectRequestLine()
  {
    $this->assertStringStartsWith(
      "POST /post/processor HTTP/1.1\r\n",
      $this->topicExportWithPostValues(),
      'Incorrect request line in generated Raw HTTP Request string.'
    );
  }

  function testExportWithPostValuesSetsCorrectHeaders()
  {
    $content = 'foo=bar&goo%5B%5D=jazz&good=bad%3D';
    $headers = array(
      'Content-Type' => 'application/x-www-form-urlencoded',
      'Content-Length' => strlen($content)
    );
    $this->_testHeaders($headers, $this->topicExportWithPostValues());
  }

  function testExportWithPostValuesSetsCorrectContent()
  {
    $content = 'foo=bar&goo%5B%5D=jazz&good=bad%3D';
    $this->assertStringEndsWith(
      "\r\n\r\n$content", $this->topicExportWithPostValues(),
      'Raw HTTP Request string should end in "\r\n\r\n' . $content . '".'
    );
  }

  private function topicExportGet()
  {
    $R = new Request(array(
      'path'    => '/a/get/path',
      'content' => array('foo' => 'bar')
    ));

    return $R->export();
  }

  function testExportGetShouldHaveNoPostLikeHeader()
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

  function testExportGetShouldHaveNoContent()
  {
    $this->assertNotContains("foo=bar\r\n\r\n", $this->topicExportGet());
  }

  function testExportRequestWithParamsUrlEncodesParamValues()
  {
    $R = new Request(array(
      'path'    => '/handler',
      'params' => array(
        'foo' => 'bar', 'goo[]' => 'jazz', 'good' => 'bad='
      )
    ));
    $expected = 'foo=bar&' . urlencode('goo[]') . '=jazz&good=bad' .
      urlencode('=');
    $str = $R->export();
    $this->assertStringStartsWith(
      "GET /handler?$expected HTTP/1.1\r\n", $str, $str
    );
  }

}
