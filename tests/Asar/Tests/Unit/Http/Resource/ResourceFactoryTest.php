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
use Asar\Routing\NullRoute;
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
        $this->container = $this->quickMock('Dimple\Container');
        $this->resourceResolver = $this->quickMock(
            'Asar\Http\Resource\ResourceResolver'
        );
        $this->factory = new ResourceFactory($this->container, $this->resourceResolver);
    }

    /**
     * Returns a resource from container
     */
    public function testReturnsAResourceFromContainer()
    {
        $route = new Route('/', $this->className, array());
        $this->resourceResolver->expects($this->once())
            ->method('getResourceClassName')
            ->with($route)
            ->will($this->returnValue($this->className));
        $this->container->expects($this->at(0))
            ->method('offsetSet')
            ->with('request.resource.class', $this->className);
        $this->container->expects($this->once())
            ->method('get')
            ->with('request.resource.default')
            ->will($this->returnValue($resource = new stdClass));
        $this->assertSame(
            $resource, $this->factory->getResource($route)
        );
    }

    /**
     * Returns a not found resource when given a NullRoute
     */
    public function testReturnsNotFoundResourceWhenGivenANullRoute()
    {
        $route = new NullRoute('/', $this->className, array());
        $this->container->expects($this->once())
            ->method('get')
            ->with('asar.resource.generic.notfound')
            ->will($this->returnValue($resource = new stdClass));
        $this->assertSame(
            $resource, $this->factory->getResource($route)
        );
    }

    /**
     * Throws an exception when a class specified in a route does not exit
     */
    public function testThrowExceptionForNonExistentClassName()
    {
        $route = new Route('/foo', 'UnknownClass', array());
        $this->resourceResolver->expects($this->once())
            ->method('getResourceClassName')
            ->will($this->returnValue('UnknownClass'));

        $this->setExpectedException(
            'Asar\Http\Resource\Exception\UnknownResourceClass',
            "Unable to find Resource with classname 'UnknownClass'."
        );
        $this->factory->getResource($route);
    }

}