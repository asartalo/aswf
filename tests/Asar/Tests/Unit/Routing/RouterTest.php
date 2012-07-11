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
        $this->resourceFactory = $this->quickMock(
            'Asar\Http\Resource\ResourceFactory', array('getResource')
        );
        $this->router = new Router($this->navigator, $this->resourceFactory);
    }

    /**
     * Test basic routing to root path
     */
    public function testRouting()
    {
        $route = new Route('Index', array());
        $this->navigator->expects($this->once())
            ->method('find')
            ->with('/')
            ->will($this->returnValue($route));
        $this->resourceFactory->expects($this->once())
            ->method('getResource')
            ->with($route);
        $this->router->route('/');
    }

    /**
     * Routing returns resource from resource factory
     */
    public function testRoutingPassesResourceFromResourceFactory()
    {
        $resource = new \stdClass;
        $this->navigator->expects($this->once())
            ->method('find')
            ->with('/')
            ->will(
                $this->returnValue(new Route('Index', array()))
            );
        $this->resourceFactory->expects($this->once())
            ->method('getResource')
            ->will($this->returnValue($resource));
        $this->assertSame($resource, $this->router->route('/'));
    }

}