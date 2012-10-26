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
use Asar\Http\Resource\Exception\ResourceNotFound;
use Asar\Http\Resource\Exception\NoRouteFound;

/**
 * Resolves routes to resource names
 */
class ResourceResolver
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
     * Gets resource class based on route
     *
     * @param Route $route the route to the resource
     *
     * @return string the resource class name
     */
    public function getResourceClassName(Route $route = null)
    {
        if (!$route) {
            throw new NoRouteFound(
                "There was no route found."
            );
        }
        $classReference = $this->config->get('namespace')
            . '\\Resource\\' . $route->getName();
        if (!class_exists($classReference)) {
            throw new ResourceNotFound(
                "Unable to find resource with class name '$classReference'."
            );
        }

        return $classReference;
    }

}