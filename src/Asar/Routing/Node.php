<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Routing;

/**
 * A route node
 */
class Node
{

    private $name;

    private $classReference;

    private $serviceId;

    private $requires;

    private $nodes = array();

    /**
     * Constructor
     *
     * Options:
     * 'requires' regular expression to match against without the '/'
     * delimiters
     *
     * @param string $name           the node name
     * @param string $classReference the nodes' classReference
     * @param array  $options        an array of options
     */
    public function __construct($name, $classReference, array $options = array())
    {
        if (strpos($name, '/') > 0) {
            throw new InvalidNodeNameException(
                "The node name '$name' contains an invalid character '/'."
            );
        }
        $this->name = $name;
        $this->classReference = $classReference;
        if (isset($options['require']) && is_string($options['require'])) {
            $this->requires = '/' . $options['require'] . '/';
        }
        if (isset($options['serviceId']) && is_string($options['serviceId'])) {
            $this->serviceId = $options['serviceId'];
        }
    }

    /**
     * Returns the node's name
     *
     * @return string this node's name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Adds a node
     *
     * @param Node $node
     */
    public function addNode(Node $node)
    {
        $this->nodes[$node->getName()] = $node;
    }

    /**
     * Adds multiple child nodes at once
     *
     * @param array $nodes a collection of nodes
     */
    public function addNodes(array $nodes)
    {
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    /**
     * Returns the number of child nodes
     *
     * @return integer the number of child nodes
     */
    public function getChildNodesCount()
    {
        return count($this->nodes);
    }

    /**
     * Returns a child node based on its node name
     *
     * @param string $name the child node's name
     *
     * @return Node the child node that has the name
     */
    public function get($name)
    {
        return $this->nodes[$name];
    }

    /**
     * Returns the node's class reference
     *
     * @return string the class reference
     */
    public function getClassReference()
    {
        return $this->classReference;
    }

    /**
     * Returns the node's service ID if set
     *
     * @return string the service ID
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Matches an argument to the nodes requirement
     *
     * @param string $toMatch the string to match against
     *
     * @return boolean whether $toMatch matches
     */
    public function matches($toMatch)
    {
        if (is_string($this->requires)) {
            return preg_match($this->requires, $toMatch) > 0;
        }

        return $this->name === $toMatch;
    }

    /**
     * Finds child nodes that match against string
     *
     * @param string $toMatch the string to match against
     *
     * @return Node a node that matches $toMatch or null if no match is found
     */
    public function findChildMatch($toMatch)
    {
        foreach ($this->nodes as $node) {
            if ($node->matches($toMatch)) {
                return $node;
            }
        }
    }

}