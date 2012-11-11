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
use Asar\Http\Resource\Dispatcher as ResourceDispatcher;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Scope;

/**
 * Creates resources.
 */
class ResourceFactory
{

    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container the DI container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        return $this->getResourceFromClassReference($route->getName());
    }

    /**
     * Gets resource based on class reference
     *
     * @param string $classReference the resource class name
     *
     * @return object the resource
     */
    private function getResourceFromClassReference($classReference)
    {
        $resource = null;
        if (class_exists($classReference)) {
            $this->container->setParameter('request.resource.class', $classReference);
            $resource = $this->container->get('request.resource.default');
            // TODO: check if this is necessary
            // reverting...
            $this->container->setParameter('request.resource.class', '');
        }

        return $resource;
    }

}