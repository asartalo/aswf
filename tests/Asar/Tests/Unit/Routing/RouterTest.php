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
use Asar\Routing\Router;
use Asar\Routing\Route;

/**
 * Specification for Asar\Routing\Router
 */
class RouterTest extends TestCase
{

    /**
     * Test setup
     */
    public function setUp()
    {
        $this->navigator = $this->quickMock(
            'Asar\Routing\NodeNavigator', array('find')
        );
        $this->router = new Router($this->navigator);
    }

    /**
     * Test basic routing to root path
     */
    public function testRouting()
    {
        $this->navigator->expects($this->once())
            ->method('find')
            ->with('/');
        $this->router->route('/');
    }

    /**
     * Routing returns route from node navigator
     */
    public function testRoutingReturnsRouteFromNodeNavigator()
    {
        $this->navigator->expects($this->once())
            ->method('find')
            ->with('/')
            ->will(
                $this->returnValue($route = new Route('/foo', 'Index', array()))
            );
        $this->assertSame($route, $this->router->route('/'));
    }

}