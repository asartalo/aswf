<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Content;

use Asar\Tests\TestCase;
use Asar\Content\Page;

/**
 * A specification for Asar\Content\Page;
 */
class PageTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->assembler = $this->quickMock(
            'Asar\Template\Engine\EngineInterface'
        );
        $this->page = new Page($this->assembler);
    }

    /**
     * Can obtain response from page
     */
    public function testGettingResponse()
    {
        $this->assertInstanceOf(
            'Asar\Http\Message\Response',
            $this->page->getResponse()
        );
    }

    /**
     * Setting a content parameter
     */
    public function testSettingContentParameterSetsRendererParameter()
    {
        $this->assembler->expects($this->once())
            ->method('set')
            ->with('foo', 'Foo');
        $this->page->set('foo', 'Foo');

    }

    /**
     * Can set response header
     */
    public function testSettingResponseHeader()
    {
        $this->page->setHeader('foo', 'bar');
        $this->assertEquals(
            'bar', $this->page->getResponse()->getHeader('foo')
        );
    }

    /**
     * Sets default content-type
     */
    public function testSetsHtmlAndUtf8AsDefaultContentType()
    {
        $this->assertEquals(
            'text/html; charset=utf-8',
            $this->page->getResponse()->getHeader('content-type')
        );
    }

    /**
     * Uses assembler render as response content
     */
    public function testUsesRendererForResponseContent()
    {
        $this->assembler->expects($this->once())
            ->method('render')
            ->will($this->returnValue('FooBar'));
        $this->assertEquals(
            'FooBar',
            $this->page->getResponse()->getContent()
        );
    }


}