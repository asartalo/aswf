<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Template\Engine;

use Asar\Tests\TestCase;
use Asar\Template\Engine\EngineRegistry;
use Asar\Template\Engine\EngineInterface;

/**
 * Specifications for EngineRegistry
 */
class EngineRegistryTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->registry = new EngineRegistry;
    }

    /**
     * Registers a template engine
     */
    public function testRegistersATemplateEngine()
    {
        $engine = $this->getMock('Asar\Template\Engine\EngineInterface');
        $this->registry->register('foo', $engine);
        $this->assertSame($engine, $this->registry->getEngine('foo'));
    }

    /**
     * Throws an exception for unregistered engines
     */
    public function testThrowsExceptionForUnregisteredTemplateEngines()
    {
        $this->setExpectedException(
            'Asar\Template\Engine\Exception\UnregisteredEngine',
            "There is no template engine registered of type 'foo'."
        );
        $this->registry->getEngine('foo');
    }

}