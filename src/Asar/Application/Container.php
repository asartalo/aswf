<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Application;

use Pimple;
use Asar\Routing\Router;
use Asar\Routing\NodeNavigator;
use Asar\Routing\NodeTreeBuilder;
use Asar\Http\Resource\ResourceFactory;
use Composer\Autoload\ClassLoader;

/**
 * Application Dependency Injection container
 */
class Container extends Pimple
{

    /**
     * Constructor
     *
     * @param array  $config  application configuration
     * @param string $appPath the application's directory
     */
    public function __construct($config, $appPath)
    {
        $this['application.config'] = $config;

        $this['application.path'] = $appPath;

        $this['application.routes'] = $config->get('routes');

        $this['asar.routeNodes'] = function($c) {
            $builder = $c['asar.routeNodesBuilder'];

            return $builder->build($c['application.routes']);
        };

        $this['classLoader'] = function($c) {
            /**
             * @todo refactor
             */
            $loader = new ClassLoader;
            $config = $c['application.config'];

            $loader->add(
                $config->get('name'), dirname($c['application.path'])
            );

            return $loader;
        };

        $this['asar.application'] = function($c) {
            return new Application($c['asar.router']);
        };

        $this['asar.router'] = function($c) {
            return new Router(
                $c['asar.nodeNavigator'],
                $c['asar.resourceFactory']
            );
        };

        $this['asar.nodeNavigator'] = function($c) {
            return new NodeNavigator(
                $c['asar.routeNodes']
            );
        };

        $this['asar.routeNodesBuilder'] = function($c) {
            return new NodeTreeBuilder;
        };

        $this['asar.resourceFactory'] = function($c) {
            return new ResourceFactory(
                $c['application.path'], $c['classLoader'],
                $c['application.config']
            );
        };
    }

}