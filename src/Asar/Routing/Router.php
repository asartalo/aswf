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
 * Routes resource paths to resources
 */
class Router implements RouterInterface
{
    private $rootNode;

    /**
     * Constructor
     *
     * @param Node $rootNode
     */
    public function __construct(Node $rootNode)
    {
        $this->rootNode = $rootNode;
    }

    /**
     * Finds a route to a node based on a path
     *
     * @param string $path
     *
     * @return Route;
     */
    public function route($path)
    {
        $pathParts = explode('/', rtrim($path, '/'));
        array_shift($pathParts);

        return $this->searchNodes($path, $pathParts);
    }


    private function searchNodes($path, $pathParts)
    {
        $currentNode = $this->rootNode;
        $pathValues = array();

        foreach ($pathParts as $subspace) {
            $foundNode = $currentNode->findChildMatch($subspace);
            if ($foundNode) {
                $pathValues[$foundNode->getName()] = $subspace;
                $currentNode = $foundNode;
            } else {
                return new NullRoute($path);
            }
        }

        return new Route(
            $path, $currentNode->getClassReference(), $pathValues,
            $currentNode->getServiceId()
        );
    }

}
