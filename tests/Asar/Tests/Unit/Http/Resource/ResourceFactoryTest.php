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
use stdClass;

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
        if (!class_exists($this->className = 'ExampleApp\Resource\FooResource')) {
            $this->createClassDefinition($this->className);
        }
        $this->container = $this->quickMock(
            'Symfony\Component\DependencyInjection\ContainerInterface'
        );
        $this->factory = new ResourceFactory($this->container);
    }

    /**
     * Returns a resource from container
     */
    public function testReturnsAResourceFromContainer()
    {
        $this->container->expects($this->at(0))
            ->method('setParameter')
            ->with('request.resource.class', $this->className);
        $this->container->expects($this->once())
            ->method('get')
            ->with('request.resource.default')
            ->will($this->returnValue($resource = new stdClass));
        $route = new Route('/', $this->className, array());
        $this->assertSame(
            $resource, $this->factory->getResource($route)
        );
    }

}