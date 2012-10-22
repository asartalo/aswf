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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Scope;

/**
 * A loader for Asar applications bootstrapping them to life
 */
class Loader
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
     * Bootstraps an application based on the configuration
     *
     * @param string $configFile the configuration file
     *
     * @return Asar\Application\Application
     */
    public static function load($configFile)
    {
        $container = new ContainerBuilder();

        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__)));
        $loader->load('services.yml');

        $container->setParameter('application.path', dirname($configFile));
        $container->setParameter('application.config.file', $configFile);
        $container->addScope(new Scope('application'));
        if (!$container->hasScope('request')) {
            $container->addScope(new Scope('request', 'application'));
        }

        $appLoader = new self($container);

        return $appLoader->loadApplication();
    }

    /**
     * Bootstraps an application
     *
     * @return Asar\Application\Application;
     */
    public function loadApplication()
    {
        $this->container->enterScope('application');
        $application = $this->container->get('application');

        return $application;
    }

}
