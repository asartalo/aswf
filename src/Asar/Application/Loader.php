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
use Asar\Config\YamlImporter;


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
        $importer = new YamlImporter(new YamlParser);
        $config = new Config(
            $configFile, array($importer)
        );
        //var_dump($config->getRaw());exit;
        $loader = new self(
            new Container($config, dirname($configFile))
        );

        return $loader->loadApplication();
    }

    /**
     * Bootstraps an application
     *
     * @return Asar\Application\Application;
     */
    public function loadApplication()
    {
        return $this->container['asar.application'];
    }

}