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

/**
 * Creates resources.
 */
class ResourceFactory
{

    private $appPath;

    private $classLoader;

    private $config;

    /**
     * Constructor
     *
     * @param string $appPath     the path to the application
     * @param object $classLoader a classloader that implements loadClass()
     *                            and follows the PSR-0 convention
     * @param Config $config      an application configuration
     */
    public function __construct($appPath, $classLoader, Config $config)
    {
        $this->appPath = $appPath;
        $this->classLoader = $classLoader;
        $this->config = $config;
    }

    /**
     * Gets resource based on resource name
     *
     * @param Route $route the route to the resource
     *
     * @return object the resource
     */
    public function getResource(Route $route)
    {
        $resourceName = $this->config->get('name')
            . '\\Resource\\' . $route->getName();

        $resource = null;
        if ($this->classLoader->loadClass($resourceName)) {
            $resource = new $resourceName;
        }

        return new ResourceDispatcher($resource);
    }

}