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

use Asar\TestHelper\TestCase;
use Asar\Routing\Node;
use Asar\Routing\Route;
use Asar\Routing\NodeNavigator;

/**
 * Specification for Asar\Routing\NodeNavigator
 */
class NodeNavigatorTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $root = new Node('root', 'RootResource');
        $child1 = new Node('foo', 'FooResource');
        $child2 = new Node('bar', 'BarResource', array('serviceId' => 'barService'));
        $grandchild1 = new Node('fooChild', 'FooChildResource');
        $child1->addNode($grandchild1);
        $root->addNodes(array($child1, $child2));

        $this->navigator = new NodeNavigator($root);
    }

    /**
     * Can get root node
     */
    public function testGetRootNode()
    {
        $this->assertEquals(
            new Route('/', 'RootResource', array()),
            $this->navigator->find('/')
        );
    }

    /**
     * Can get child node
     */
    public function testGetChildNode()
    {
        $this->assertEquals(
            new Route('/foo', 'FooResource', array('foo' => 'foo')),
            $this->navigator->find('/foo')
        );
    }

    /**
     * Can get route to second child node with service ID
     */
    public function testGetRouteWithServiceId()
    {
        $this->assertEquals(
            new Route('/bar', 'BarResource', array('bar' => 'bar'), 'barService'),
            $this->navigator->find('/bar')
        );
    }

    /**
     * Can get grandchild node
     */
    public function testGetGrandchildNode()
    {
        $this->assertEquals(
            new Route(
                '/foo/fooChild',
                'FooChildResource',
                array('foo' => 'foo', 'fooChild' => 'fooChild')
            ),
            $this->navigator->find('/foo/fooChild')
        );
    }

    /**
     * Returns null route when no node is found
     */
    public function testReturnsNullRouteForNotFoundNode()
    {
        $this->assertTrue($this->navigator->find('/foo/fooooo')->isNull());
    }

    /**
     * Passes path to null route
     */
    public function testPassesPathToNullRoute()
    {
        $this->assertEquals(
            '/foo/bar/baz',
            $this->navigator->find('/foo/bar/baz')->getPath()
        );
    }

}