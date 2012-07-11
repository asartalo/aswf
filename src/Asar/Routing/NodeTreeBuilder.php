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
 * Builds a tree of Nodes
 */
class NodeTreeBuilder
{

    /**
     * Builds a tree of Nodes
     *
     * @param array $nodeArray a representation of a tree of nodes
     *                         in array form
     *
     * @return Node a tree of nodes based on $nodeArray
     */
    public function build(array $nodeArray)
    {
        $nodes = $this->_buildNodes($nodeArray);

        return $nodes[0];
    }

    private function _buildNodes(array $nodeArray)
    {
        $nodes = array();
        foreach ($nodeArray as $nodeName => $properties) {
            $options = array();
            if (isset($properties['require'])) {
                $options['require'] = $properties['require'];
            }
            if (!isset($properties['resourceName'])) {
                throw new NoResourceNameException(
                    "The node '$nodeName' does not include a resourceName."
                );
            }
            $node = new Node($nodeName, $properties['resourceName'], $options);
            if (isset($properties['nodes'])) {
                $subNodes = $properties['nodes'];
                $node->addNodes($this->_buildNodes($subNodes));
            }
            $nodes[] = $node;
        }

        return $nodes;
    }

}