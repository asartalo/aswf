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
use Symfony\Component\DependencyInjection\ContainerInterface;

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
     * @param ContainerInterface $container the DI container
     * @param ResourceResolver   $resolver  a resource name resolver
     */
    public function __construct(ContainerInterface $container, ResourceResolver $resolver)
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
        $classReference = $this->resolver->getResourceClassName($route);
        if (!$route->isNull() && !class_exists($classReference)) {
            throw new UnknownResourceClass("Unable to find Resource with classname '$classReference'.");
        }

        return $this->getResourceFromClassReference(
            $route->getServiceName(),
            $classReference
        );

    }

    /**
     * Gets resource based on class reference
     *
     * @param string $service        the name of the service for the container to use
     * @param string $classReference the resource class name
     *
     * @return object the resource
     */
    private function getResourceFromClassReference($service, $classReference)
    {
        $this->container->setParameter('request.resource.class', $classReference);
        $resource = $this->container->get($service);
        // TODO: check if this is necessary
        // reverting...
        $this->container->setParameter('request.resource.class', '');

        return $resource;
    }

}