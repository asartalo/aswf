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
 * Navigates a Node Tree
 */
class NodeNavigator
{

    private $rootNode;

    /**
     * @param Node $rootNode
     */
    public function __construct(Node $rootNode)
    {
        $this->rootNode = $rootNode;
    }

    /**
     * Finds a node based on a path
     *
     * @param string $path
     *
     * @return Route
     */
    public function find($path)
    {
        $path = rtrim($path, '/');
        $pathParts = explode('/', $path);
        array_shift($pathParts);

        $currentNode = $this->rootNode;
        $pathValues = array();

        foreach ($pathParts as $subspace) {
            $foundNode = $currentNode->findChildMatch($subspace);
            if ($foundNode) {
                $pathValues[$foundNode->getName()] = $subspace;
                $currentNode = $foundNode;
            } else {
                return;
            }
        }

        return new Route($currentNode->getClassReference(), $pathValues);
    }

}