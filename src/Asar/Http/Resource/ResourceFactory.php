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

    private $appPath;

    private $config;

    private $container;

    /**
     * Constructor
     *
     * @param string             $appPath   the path to the application
     * @param Config             $config    an application configuration
     * @param ContainerInterface $container the DI container
     */
    public function __construct(
        $appPath,
        Config $config,
        ContainerInterface $container
    )
    {
        $this->appPath = $appPath;
        $this->config = $config;
        $this->container = $container;
    }

    /**
     * Gets resource based on class reference
     *
     * @param Route $route the route to the resource
     *
     * @return object the resource
     */
    public function getResource(Route $route)
    {
        $classReference = $this->config->get('namespace')
            . '\\Resource\\' . $route->getName();
        $resource = null;
        if (class_exists($classReference)) {
            $this->container->enterScope('request');
            $this->container->setParameter('request.resource.class', $classReference);
            $resource = $this->container->get('request.resource');
        }

        return new ResourceDispatcher($resource);
    }

}