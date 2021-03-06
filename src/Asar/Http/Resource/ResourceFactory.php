<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Http\Resource;

use Asar\Routing\Route;
use Asar\Config\Config;
use Asar\Http\Resource\ResourceResolver;
use Asar\Http\Resource\Exception\UnknownResourceClass;
use Dimple\Container;

/**
 * Creates resources.
 */
class ResourceFactory
{

    private $container;

    private $resolver;

    /**
     * Constructor
     *
     * @param Container        $container the DI container
     * @param ResourceResolver $resolver  a resource name resolver
     */
    public function __construct(Container $container, ResourceResolver $resolver)
    {
        $this->container = $container;
        $this->resolver = $resolver;
    }

    /**
     * Gets resource based on route
     *
     * @param Route $route the route
     *
     * @return object the resource
     */
    public function getResource(Route $route)
    {
        $className = $this->resolver->getResourceClassName($route);
        if (!$route->isNull() && !class_exists($className)) {
            throw new UnknownResourceClass("Unable to find Resource with classname '$className'.");
        }

        return $this->getResourceFromClassName(
            $route->getServiceId(),
            $className
        );

    }

    /**
     * Gets resource based on class reference
     *
     * @param string $service   the name of the service for the container to use
     * @param string $className the resource class name
     *
     * @return object the resource
     */
    private function getResourceFromClassName($service, $className)
    {
        $this->container['request.resource.class'] = $className;
        $resource = $this->container->get($service);
        // TODO: check if this is necessary
        // reverting...
        $this->container['request.resource.class'] = '';

        return $resource;
    }

}