<?php

namespace Asar\Tests\Unit\Http;

use Asar\Http\Url;

class UrlTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataCreatingAUrl
   */
  function testCreatingAUrl($method, $expected) {
    $url = new Url(
      'http://john:secret@example.com:8042/over/there/index.dtb?' .
      'type=animal&name=narwhal#nose'
    );
    $this->assertEquals($expected, $url->$method());
  }

  /**
   * @dataProvider dataCreatingAUrl
   */
  function testAlternativeCreationOfUrl($method, $expected) {
    $url = new Url(array(
      'scheme'   => 'http',
      'username' => 'john',
      'password' => 'secret',
      'host'     => 'example.com',
      'path'     => '/over/there/index.dtb',
      'port'     => 8042,
      'query'    => array( 'type' => 'animal', 'name' => 'narwhal'),
      'fragment' => 'nose'
    ));
    $this->assertEquals($expected, $url->$method());
  }

  function dataCreatingAUrl() {
    return array(
      array('getScheme',      'http'),
      array('getUsername',    'john'),
      array('getPassword',    'secret'),
      array('getPath',        '/over/there/index.dtb'),
      array('getPort',        '8042'),
      array('getQueryString', 'type=animal&name=narwhal'),
      array('getParameters',  array( 'type' => 'animal', 'name' => 'narwhal')),
      array('getParamString', 'type=animal&name=narwhal'),
      array('getQuery',       array( 'type' => 'animal', 'name' => 'narwhal')),
      array('getFragment',    'nose'),
      array('__toString',     'http://john:secret@example.com:8042' .
                              '/over/there/index.dtb?' .
                              'type=animal&name=narwhal#nose')
    );
  }

  /**
   * @dataProvider dataMinimumAUrlForm
   */
  function testMinimumUrlForm($method, $expected) {
    $url = new Url(array(
      'host'     => 'example.com',
      'path'     => '/over/there/index.dtb',
    ));
    $this->assertEquals($expected, $url->$method());
  }

  function dataMinimumAUrlForm() {
    return array(
      array('getScheme',      'http'),
      array('getUsername',    ''),
      array('getPassword',    ''),
      array('getPath',        '/over/there/index.dtb'),
      array('getPort',        80),
      array('getParameters',  array()),
      array('getParamString', ''),
      array('getFragment',    ''),
      array('__toString',     'http://example.com/over/there/index.dtb')
    );
  }

  function testDefaultPortForHttpsIs443() {
    $url = new Url('https://example.com');
    $this->assertEquals(443, $url->getPort());
  }

}