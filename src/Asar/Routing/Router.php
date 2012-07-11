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

use Asar\Http\Resource\ResourceFactory;

/**
 * Routes resource paths to resources
 */
class Router implements RouterInterface
{
    private $navigator;

    private $resourceFactory;

    /**
     * Constructor
     *
     * @param NodeNavigator   $navigator       a map of routes to resources
     * @param ResourceFactory $resourceFactory a resource factory
     */
    public function __construct(
        NodeNavigator $navigator, ResourceFactory $resourceFactory
    )
    {
        $this->navigator = $navigator;
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @param string $path
     *
     * @return Resource;
     */
    public function route($path)
    {
        $route = $this->navigator->find($path);
        return $this->resourceFactory->getResource($route);
    }

}