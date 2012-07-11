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

use Asar\Routing\Router;
use Asar\Http\Resource\ResourceFactory;
use Asar\Application\Container;
use Asar\Config\Config;

use Symfony\Component\Yaml\Parser as YamlParser;

/**
 * A loader for Asar applications bootstrapping them to life
 */
class Loader
{
    private $container;

    /**
     * Constructor
     *
     * @param ContainerBuilder $container DI container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Bootstraps an application based on the configuration
     *
     * @param string $configFile the configuration file
     *
     * @return Asar\Application\Application
     */
    public static function load($configFile)
    {
        $configFile = realpath($configFile);
        $yamlParser = new YamlParser;
        $config = new Config($yamlParser->parse(
            file_get_contents($configFile)
        ));
        $loader = new self(
            new Container($config, dirname($configFile))
        );

        return $loader->loadApplication($config);
    }

    /**
     * Bootstraps an application based on the configuration
     *
     * @param array $config application configuration
     *
     * @return Asar\Application\Application;
     */
    public function loadApplication($config = array())
    {
        $application = $this->container['asar.application'];

        return $application;
    }

}