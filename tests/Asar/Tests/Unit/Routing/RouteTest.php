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
        $this->pathValues = array(
            'foo' => 'foo',
            'bar' => 'Bar',
            'baz' => 'EndPoint'
        );
        $this->route = new Route('ResourceName', $this->pathValues);
    }


    /**
     * Can have path values
     */
    public function testRouteHasPathValues()
    {
        $this->assertEquals($this->pathValues, $this->route->getValues());
    }

    /**
     * Can get name
     */
    public function testGetName()
    {
        $this->assertEquals('ResourceName', $this->route->getName());
    }

}