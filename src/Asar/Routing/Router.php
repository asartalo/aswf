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
    private $navigator;

    /**
     * Constructor
     *
     * @param NodeNavigator $navigator a map of routes to resources
     */
    public function __construct(NodeNavigator $navigator)
    {
        $this->navigator = $navigator;
    }

    /**
     * @param string $path
     *
     * @return Resource;
     */
    public function route($path)
    {
        return $this->navigator->find($path);
    }

}