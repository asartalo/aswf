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
use Asar\Template\TemplateAssembly;

/**
 * Specifications for TemplateAssembly
 */
class TemplateAssemblyTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->engine = $this->quickMock('Asar\Template\Engine\EngineInterface');
        $this->type = 'foo';
        $this->file = '/the/path/to/the/file';
        $this->assembly = new TemplateAssembly($this->file, $this->type, $this->engine);
    }

    /**
     * Can get file
     */
    public function testGetFile()
    {
        $this->assertEquals($this->file, $this->assembly->getFile());
    }

    /**
     * Can get type
     */
    public function testGetType()
    {
        $this->assertEquals($this->type, $this->assembly->getType());
    }

    /**
     * Can get the engine
     */
    public function testGetEngine()
    {
        $this->assertSame($this->engine, $this->assembly->getEngine());
    }

    /**
     * Renders a template using template engine
     */
    public function testRendersATemplateUsingTemplateEngine()
    {
        $params = array('foo' => 'bar');
        $this->engine->expects($this->once())
            ->method('render')
            ->with($this->file, $params);
        $this->assembly->render($params);
    }

    /**
     * Uses output from template engine
     */
    public function testRenderUsesOutputFromTemplateEngine()
    {
        $this->engine->expects($this->once())
            ->method('render')
            ->will($this->returnValue($output = 'FooBar'));
        $this->assertEquals($output, $this->assembly->render());
    }

}