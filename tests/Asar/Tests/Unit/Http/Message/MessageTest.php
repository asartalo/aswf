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

use Asar\Http\Message\Message;
use Asar\Tests\TestCase;

/**
 * A specification for Asar\Http\Message\Message
 */
class MessageTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->message = new ConcreteMessage;
    }

    /**
     * Should be able to set content
     */
    public function testShouldBeAbleToSetContent()
    {
        $this->message->setContent(array('bar'=>'foo'));
        $this->assertEquals(
            array('bar'=>'foo'), $this->message->getContent(),
            'Unable to set content for Message object'
        );
    }

    /**
     * Message returns empty string as content by default
     */
    public function testMessagReturnsEmptyStringAsContentByDefault()
    {
        $this->assertSame('', $this->message->getContent());
    }

    /**
     * Returns null for unknown headers
     */
    public function testGettingUnknownHeaderReturnsNull()
    {
        $this->assertNull($this->message->getHeader('foo'));
    }

    /**
     * Header field names should be case-insensitive
     */
    public function testHeaderFieldNamesShouldBeCaseInsensitive()
    {
        $this->message->setHeader('SoMe-fiEld-Name', 'Foo Bar');
        $this->assertEquals(
            'Foo Bar', $this->message->getHeader('somE-Field-nAmE'),
            'Field names for headers should be case-insensitive.'
        );
    }

    /**
     * Can set multiple headers at once
     */
    public function testMultipleSettingOfHeaderFields()
    {
        $this->message->setHeaders(array(
            'A-Field' => 'goo',
            'Another-Field' => 'bar',
            'Some-Other-Field' => 'car'
        ));
        $headers = $this->message->getHeaders();
        $this->assertEquals(
            'goo', $headers['A-Field'],
            'Header field was not found.'
        );
        $this->assertEquals(
            'bar', $headers['Another-Field'],
            'Header field was not found.'
        );
        $this->assertEquals(
            'car', $headers['Some-Other-Field'],
            'Header field was not found.'
        );
    }

    /**
     * Header field names are converted to Dashed-Camel-Case
     */
    public function testHeaderFieldNamesShouldBeConvertedToDashedCamelCase()
    {
        $this->message->setHeader('afield', 'foo');
        $this->message->setHeader('yet-aNotHEr-Field', 'bar');
        $this->message->setHeader('And-yet-anOther', 'jar');
        $headers = $this->message->getHeaders();
        $this->assertTrue(
            array_key_exists('Afield', $headers),
            'Header field name was not converted to Dashed-Camel-Case.'
        );
        $this->assertTrue(
            array_key_exists('Yet-Another-Field', $headers),
            'Header field name was not converted to Dashed-Camel-Case.'
        );
        $this->assertTrue(
            array_key_exists('And-Yet-Another', $headers),
            'Header field name was not converted to Dashed-Camel-Case.'
        );
    }

    /**
     * Basic tests for setting HTTP headers
     */
    public function testSettingMessageHeaders()
    {
        // Some common HTTP Message headers...
        $headers = array(
            'User-Agent'    => 'Mozilla/5.0',
            'Accept'      => 'text/html',
            'Accept-Charset'  => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'Accept-Language' => 'tl,en-us;q=0.7,en;q=0.3',
            'Accept-Encoding' => 'gzip,deflate',
            'Host'      => 'somehost.com'
        );
        $this->message->setHeaders($headers);
        foreach ($headers as $key => $value) {
            $this->assertEquals(
                $value, $this->message->getHeader($key),
                "Unable to obtain $key request header."
            );
        }
    }

    /**
     * Setting a header to null will unset it
     */
    public function testSettingHeaderToNullWillUnsetIt()
    {
        $this->message->setHeader('Foo', 'bar');
        $this->message->setHeader('Foo', null);
        $this->assertFalse(array_key_exists('Foo', $this->message->getHeaders()));
    }

    /**
     * Can unset a header using method
     */
    public function testMethodForUnsettingHeader()
    {
        $this->message->setHeader('Foo', 'bar');
        $this->message->unsetHeader('Foo');
        $this->assertFalse(array_key_exists('Foo', $this->message->getHeaders()));
    }

    /**
     * Can set content on creation
     */
    public function testSettingContentOnCreation()
    {
        $message = new ConcreteMessage(array('content' => 'foo bar'));
        $this->assertEquals('foo bar', $message->getContent());
    }

    /**
     * Can set request headers on creation
     */
    public function testRequestSettingHeadersOnCreation()
    {
        $message = new ConcreteMessage(
            array('headers' => array('foo' => 'Foo', 'bar' => 'Baz'))
        );
        $this->assertEquals('Foo', $message->getHeader('foo'));
        $this->assertEquals('Baz', $message->getHeader('bar'));
    }

}
