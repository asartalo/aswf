<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Http\Resource;

use Asar\Tests\TestCase;
use Asar\Http\Resource\ResourceFactory;
use Asar\Config\Config;
use Asar\Routing\Route;

/**
 * Specifications for Asar\Http\Resource\ResourceFactory
 */
class ResourceFactoryTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        if (!class_exists('ExampleApp\Resource\FooResource')) {
            $this->createClassDefinition('ExampleApp\Resource\FooResource');
        }
        $this->classLoader = $this->quickMock(
            'Composer\Autoload\ClassLoader', array('loadClass')
        );
        $this->appPath = '/path/to/application';
        $this->config  = new Config(array('name' => 'ExampleApp'));
        $this->factory = new ResourceFactory(
            $this->appPath, $this->classLoader, $this->config
        );
    }

    /**
     * Uses classloader to load resource
     */
    public function testUsesClassLoaderToLoadResource()
    {
        $this->classLoader->expects($this->once())
            ->method('loadClass')
            ->with('ExampleApp\Resource\FooResource');
        $this->factory->getResource(new Route('FooResource', array()));
    }

    /**
     * Returns a Request Handler
     */
    public function testReturnsAResourceWrappedByResourceDispatcher()
    {
        $this->assertInstanceOf(
            'Asar\Http\RequestHandlerInterface',
            $this->factory->getResource(new Route('FooResource', array()))
        );
    }

    /**
     * Creates dispatcher with resource
     */
    public function testCreatesDispatcherThatWrapsResource()
    {
        $this->classLoader->expects($this->once())
            ->method('loadClass')
            ->will($this->returnValue(true));
        $dispatcher = $this->factory->getResource(
            new Route('FooResource', array())
        );
        $this->assertInstanceOf(
            'ExampleApp\Resource\FooResource', $dispatcher->getResource()
        );
    }

    /**
     * Creates empty dispatcher when resource does not exist
     */
    public function testCreatesEmptyDispatcherWhenThereIsNoResourceFound()
    {
        $this->classLoader->expects($this->once())
            ->method('loadClass')
            ->will($this->returnValue(false));
        $dispatcher = $this->factory->getResource(
            new Route('FooResource', array())
        );
        $this->assertNull($dispatcher->getResource());
    }

}