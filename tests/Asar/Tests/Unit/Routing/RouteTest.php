<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Routing;

use Asar\Tests\TestCase;
use Asar\Routing\Route;

/**
 * Specification for Asar\Routing\Route
 */
class RouteTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->path = '/foo/Bar/EndPoint';
        $this->pathValues = array(
            'foo' => 'foo',
            'bar' => 'Bar',
            'baz' => 'EndPoint'
        );
        $this->route = new Route($this->path, 'ResourceClassReference', $this->pathValues);
    }

    /**
     * Get get original path
     */
    public function testCanGetOriginalPath()
    {
        $this->assertEquals($this->path, $this->route->getPath());
    }

    /**
     * Can get name
     */
    public function testGetName()
    {
        $this->assertEquals('ResourceClassReference', $this->route->getName());
    }

    /**
     * Can have path values
     */
    public function testRouteHasPathValues()
    {
        $this->assertEquals($this->pathValues, $this->route->getValues());
    }

    /**
     * Is not null
     */
    public function testIsNotNull()
    {
        $this->assertFalse($this->route->isNull());
    }

    /**
     * Has default service name set
     */
    public function testHasDefaultServiceName()
    {
        $this->assertEquals('request.resource.default', $this->route->getServiceName());
    }

}