<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Template;

use Asar\Tests\TestCase;
use Asar\Routing\Route;
use Asar\Http\Message\Request;
use Asar\Template\Assembler;

/**
 * Specifications for Assembler
 */
class AssemblerTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->route = new Route('FooResource', array());
        $this->finder = $this->quickMock(
            'Asar\Template\TemplateFinder'
        );
        $this->request = new Request(array('path' => '/foo'));
    }

    /**
     * Test basic assembly
     */
    public function testBasicAssemblyUsesFinder()
    {
        $this->assembler = new Assembler($this->finder, $this->request);
        $this->finder->expects($this->once())
            ->method('find')
            ->with('FooResource', array('type' => 'html', 'method' => 'GET'));
        $this->assembler->getTemplate($this->route);
    }

    /**
     * Uses request method when finding template
     */
    public function testUsesRequestMethodToFindTemplate()
    {
        $this->request->setMethod('POST');
        $this->assembler = new Assembler($this->finder, $this->request);
        $this->finder->expects($this->once())
            ->method('find')
            ->with('FooResource', array('type' => 'html', 'method' => 'POST'));
        $this->assembler->getTemplate($this->route);
    }

    /**
     * Returns an assembled template engine
     */
    public function testReturnsTemplateEngine()
    {
        $this->assembler = new Assembler($this->finder, $this->request);
        $this->finder->expects($this->once())
            ->method('find')
            ->will($this->returnValue(array('/foo/bar.template.file')));
        $engine = $this->assembler->getTemplate($this->route);
        $this->assertInstanceOf('Asar\Template\Engine\EngineInterface', $engine);
    }

}