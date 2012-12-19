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

/**
 * Specification for Asar\Routing\Node
 */
class NodeTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->node = new Node('foo', 'FooResource');
    }

    /**
     * A node has a name
     */
    public function testHasAName()
    {
        $this->assertEquals('foo', $this->node->getName());
    }

    /**
     * A node name must not contain '/'
     */
    public function testNodeNameMustNotHaveForwardSlash()
    {
        $this->setExpectedException(
            'Asar\Routing\InvalidNodeNameException'
        );
        $this->node = new Node('fo/o', 'FooResource');
    }

    /**
     * A node has no child nodes by default
     */
    public function testHasNoChildNodesFirst()
    {
        $this->assertEquals(0, $this->node->getChildNodesCount());
    }

    /**
     * Can have child nodes
     */
    public function testCanHaveChildNodes()
    {
        $this->node->addNode(new Node('bar', 'BarResource'));
        $this->assertEquals(1, $this->node->getChildNodesCount());
    }

    /**
     * Can obtain child nodes by name
     */
    public function testCanGetNodesByName()
    {
        $this->node->addNode($childNode = new Node('bar', 'BarResource'));
        $this->assertSame($childNode, $this->node->get('bar'));
    }

    /**
     * Has a class reference
     */
    public function testGetClassReference()
    {
        $this->assertEquals('FooResource', $this->node->getClassReference());
    }

    /**
     * Can have a service id
     */
    public function testNodesCanHaveServiceIds()
    {
        $node = new Node('foo', 'FooResource', array('serviceId' => 'fooService'));
        $this->assertEquals('fooService', $node->getServiceId());
    }

    /**
     * A node matches its name
     */
    public function testMatchesItsName()
    {
        $this->assertFalse($this->node->matches('bar'), 'Must not match wrong name.');
        $this->assertTrue($this->node->matches('foo'), 'Must match name.');
    }

    /**
     * A node matches against required regex if it has one
     */
    public function testMatchesRequiredRegex()
    {
        $node = new Node('foo', 'FooResource', array('require' => '^\d{4}$'));
        $this->assertFalse($node->matches('foo'), 'Must not match wrong value.');
        $this->assertTrue($node->matches('1234'), 'Must match correct value.');
    }

    /**
     * A node can find a child that matches
     */
    public function testFindChildThatMatches()
    {
        $child1 = new Node('baa', 'Resource');
        $child2 = new Node('bar', 'Resource');
        $child3 = new Node('baz', 'Resource');

        $this->node->addNode($child1);
        $this->node->addNode($child2);
        $this->node->addNode($child3);

        $this->assertEquals($child2, $this->node->findChildMatch('bar'));
    }

    /**
     * Can add multiple child Nodes at once
     */
    public function testAddMultipleChildNodesAtOnce()
    {
        $child1 = new Node('baa', 'Resource');
        $child2 = new Node('bar', 'Resource');
        $child3 = new Node('baz', 'Resource');

        $this->node->addNodes(array($child1, $child2, $child3));

        $this->assertEquals($child2, $this->node->findChildMatch('bar'));
    }

}