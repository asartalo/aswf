<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Http;

use Asar\TestHelper\TestCase;
use Asar\Http\Url;

/**
 * Specifications for the URL object
 */
class UrlTest extends TestCase
{

    /**
     * Can create a URL
     *
     * @param string $method   method to obtain data from URL object
     * @param mixed  $expected expected value of data
     *
     * @dataProvider dataCreatingAUrl
     */
    public function testCreatingAUrl($method, $expected)
    {
        $url = new Url(
            'http://john:secret@example.com:8042/over/there/index.dtb?' .
            'type=animal&name=narwhal#nose'
        );
        $this->assertEquals($expected, $url->$method());
    }

    /**
     * Alternative creation of URL
     *
     * @param string $method   method to obtain data from URL object
     * @param mixed  $expected expected value of data
     *
     * @dataProvider dataCreatingAUrl
     */
    public function testAlternativeCreationOfUrl($method, $expected)
    {
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

    /**
     * Data provider for creating a URL
     *
     * @return array test data
     */
    public function dataCreatingAUrl()
    {
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
     * Tests minimum URL form data required
     *
     * @param string $method   method to obtain data from URL object
     * @param mixed  $expected expected value of data
     *
     * @dataProvider dataMinimumAUrlForm
     */
    public function testMinimumUrlForm($method, $expected)
    {
        $url = new Url(array(
            'host'     => 'example.com',
            'path'     => '/over/there/index.dtb',
        ));
        $this->assertEquals($expected, $url->$method());
    }

    /**
     * Data provider for minimum url form test
     *
     * @return array test data
     */
    public function dataMinimumAUrlForm()
    {
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

    /**
     * Default port for HTTPS is 443
     */
    public function testDefaultPortForHttpsIs443()
    {
        $url = new Url('https://example.com');
        $this->assertEquals(443, $url->getPort());
    }

}
