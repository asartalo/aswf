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
use Asar\Routing\NullRoute;

/**
 * Specification for Asar\Routing\NullRoute
 */
class NullRouteTest extends TestCase
{

    /**
     * Setup
     */
    public function setup()
    {
        $this->nullRoute = new NullRoute('/foo');
    }

    /**
     * Is Null
     */
    public function testIsNull()
    {
        $this->assertTrue($this->nullRoute->isNull());
    }

    /**
     * Is a Route
     */
    public function testIsARoute()
    {
        $this->assertInstanceOf('Asar\Routing\Route', $this->nullRoute);
    }

    /**
     * Can get original input path
     */
    public function testCanGetOriginalPath()
    {
        $this->assertEquals('/foo', $this->nullRoute->getPath());
    }

    /**
     * Has no name
     */
    public function testHasNoName()
    {
        $this->assertEquals('', $this->nullRoute->getName());
    }

    /**
     * Has no values
     */
    public function testHasNoValues()
    {
        $this->assertEmpty($this->nullRoute->getValues());
    }

    /**
     * Uses Generic resource as service name
     */
    public function testUsesGenericResourceAsServiceName()
    {
        $this->assertEquals(
            'asar.resource.generic.notfound', $this->nullRoute->getServiceId()
        );
    }

}
