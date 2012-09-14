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
        $this->page = new Page;
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
    public function testSettingContentParameterReturnsResponseWithContent()
    {
        $this->page->set('foo', 'Foo');
        $this->assertContains('Foo', $this->page->getResponse()->getContent());
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
            'text/html; charset=utf-8', $this->page->getResponse()->getHeader('content-type')
        );
    }


}