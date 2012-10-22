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
use Asar\Http\Message\Response;
use Asar\Http\Message\Request;
use Asar\Routing\Route;
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
        $this->templateAssembler = $this->quickMock(
            'Asar\Template\TemplateAssembler'
        );
        $this->template = $this->quickMock(
            'Asar\Template\TemplateAssembly'
        );
        $this->route = new Route('FooResource', array());
        $this->request = new Request;
        $this->page = new Page($this->templateAssembler, $this->route, $this->request);
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
     * Uses template assembler to obtain template
     */
    public function testUsesTemplateAssembler()
    {
        $this->templateAssembler->expects($this->once())
            ->method('find')
            ->with('FooResource', array('type' => 'html', 'method' => 'GET'));
        $this->page->getResponse();
    }


    private function assemblerReturnsTemplate()
    {
        $this->templateAssembler->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->template));
    }

    /**
     * Setting a content parameter
     */
    public function testSettingContentParameterSetsTemplateParameter()
    {
        $this->assemblerReturnsTemplate();
        $this->template->expects($this->once())
            ->method('render')
            ->with(array('foo' => 'Foo'));
        $this->page->set('foo', 'Foo');
        $this->page->getResponse();

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
     * Uses template render output as response content
     */
    public function testUsesRendererForResponseContent()
    {
        $this->assemblerReturnsTemplate();
        $this->template->expects($this->once())
            ->method('render')
            ->will($this->returnValue('FooBar'));
        $this->assertEquals(
            'FooBar',
            $this->page->getResponse()->getContent()
        );
    }


}