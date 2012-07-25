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

    private $config;

    /**
     * Constructor
     *
     * @param string $appPath the path to the application
     * @param Config $config  an application configuration
     */
    public function __construct($appPath, Config $config)
    {
        $this->appPath = $appPath;
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
        $resourceName = $this->config->get('namespace')
            . '\\Resource\\' . $route->getName();
        $resource = null;
        if (class_exists($resourceName)) {
            $resource = new $resourceName;
        }

        return new ResourceDispatcher($resource);
    }

}