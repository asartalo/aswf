<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Http\Resource\Generic;

use Asar\TestHelper\TestCase;
use Asar\Http\Resource\Generic\NotFound;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;
use Asar\Template\Exception\TemplateFileNotFound;

/**
 * Specification for Asar\Http\Resource\Generic\NotFound
 */
class NotFoundTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->page = $this->quickMock('Asar\Content\Page', array('getResponse', 'setStatus'));
        $this->notFound = new NotFound($this->page);
    }

    /**
     * Always returns a 404 response status
     */
    public function testReturns404ResponseStatus()
    {
        $methods = array('GET', 'POST', 'PUT', 'DELETE');
        foreach ($methods as $method) {
            $request = new Request(array('method' => $method));
            $this->assertEquals(404, $this->notFound->$method($request)->getStatus());
        }
    }

    /**
     * Returns response from page if there are no exceptions
     */
    public function testReturnsResponseFromPageIfThereAreNoExceptions()
    {
        $request = new Request(array('method' => 'GET'));
        $response = new Response(array('status' => 404));
        $this->page->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($response));
        $this->assertSame($response, $this->notFound->GET($request));
    }

    /**
     * Sets the page status to 404 if available
     */
    public function testSetsPageStatusCodeTo404()
    {
        $this->page->expects($this->once())
            ->method('setStatus')
            ->with(404);
        $this->notFound->GET(new Request);
    }

    /**
     * Returns a generic 404 response if there's no template file
     */
    public function testReturnGeneric404ResponseIfNoPageTemplate()
    {
        $this->page->expects($this->once())
            ->method('getResponse')
            ->will($this->throwException( new TemplateFileNotFound ));
        $response = $this->notFound->GET(new Request);
        $this->assertEquals(404, $response->getStatus());
    }

}
