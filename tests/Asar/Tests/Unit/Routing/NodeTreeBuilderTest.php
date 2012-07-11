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
use Asar\Routing\Node;
use Asar\Routing\NodeTreeBuilder;

/**
 * Specification for Asar\Routing\NodeTreeBuilder
 */
class NodeTreeBuilderTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->builder = new NodeTreeBuilder;
        $this->nodeArray = array(
            'root' => array(
                'resourceName' => 'RootResource',
                'nodes' => array(
                    'foo' => array(
                        'resourceName' => 'FooResource',
                        'require'      => '^\d+$',
                    ),
                    'bar' => array(
                        'resourceName' => 'BarResource'
                    )
                )
            )
        );
        $this->rootNode = $this->builder->build($this->nodeArray);
    }

    /**
     * Builds a tree of Nodes
     */
    public function testBuilderBuildsTreeOfNodes()
    {
        $this->assertInstanceOf('Asar\Routing\Node', $this->rootNode);
    }

    /**
     * Builds a tree of Nodes with correct root node
     */
    public function testBuiltTreeHasProperRootNode()
    {
        $this->assertEquals('root', $this->rootNode->getName());
        $this->assertEquals('RootResource', $this->rootNode->getResourceName());
    }

    /**
     * Builds child nodes
     */
    public function testBuiltTreeWithCorrectChildNodes()
    {
        $this->assertEquals(
            'FooResource', $this->rootNode->get('foo')->getResourceName()
        );
        $this->assertEquals(
            'BarResource', $this->rootNode->get('bar')->getResourceName()
        );
    }

    /**
     * Can build nodes with requires
     */
    public function testBuildTreeWithNodesWithRequire()
    {
        $this->assertTrue(
            $this->rootNode->get('foo')->matches('12345')
        );
    }

    /**
     * Will throw an exception if node did not include resource name
     */
    public function testThrowExceptionWhenNodeDoesNotIncludeResourceName()
    {
        $this->setExpectedException(
            'Asar\Routing\NoResourceNameException'
        );
        $nodeArray = array(
            'root' => array(
                'resourceName' => 'RootResource',
                'nodes' => array(
                    'foo' => array(),
                )
            )
        );
        $rootNode = $this->builder->build($nodeArray);
    }

}