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
                'classRef' => 'RootResource',
                'nodes' => array(
                    'foo' => array(
                        'classRef' => 'FooResource',
                        'require'      => '^\d+$',
                    ),
                    'bar' => array(
                        'classRef' => 'BarResource',
                        'serviceId' => 'service.BarResource'
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
        $this->assertEquals('RootResource', $this->rootNode->getClassReference());
    }

    /**
     * Builds child nodes
     */
    public function testBuiltTreeWithCorrectChildNodes()
    {
        $this->assertEquals(
            'FooResource', $this->rootNode->get('foo')->getClassReference()
        );
        $this->assertEquals(
            'BarResource', $this->rootNode->get('bar')->getClassReference()
        );
    }

    /**
     * Builds nodes with service ID specified
     */
    public function testBuildsNodesWithServiceIdSpecified()
    {
        $this->assertEquals(
            'service.BarResource', $this->rootNode->get('bar')->getServiceId()
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
    public function testThrowExceptionWhenNodeDoesNotIncludeclassRef()
    {
        $this->setExpectedException(
            'Asar\Routing\NoclassReferenceException'
        );
        $nodeArray = array(
            'root' => array(
                'classRef' => 'RootResource',
                'nodes' => array(
                    'foo' => array(),
                )
            )
        );
        $rootNode = $this->builder->build($nodeArray);
    }

}