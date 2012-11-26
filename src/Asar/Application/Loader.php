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

use Asar\Utilities\Framework as FrameworkUtility;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
     * @param string $configFile an application configuration file
     *
     * @return Asar\Application\Application
     */
    public static function load($configFile)
    {
        $appLoader = self::getAppLoader($configFile);

        return $appLoader->loadApplication();
    }

    /**
     * Gets apploader
     *
     * @param string $configFile an application configuration file
     *
     * @return Loader
     */
    public static function getAppLoader($configFile)
    {
        $container = new ContainerBuilder();

        $framework = new FrameworkUtility;

        $loader = new YamlFileLoader(
            $container, new FileLocator($framework->getResourcesPath())
        );

        $loader->load('services.yml');

        $container->set('asar.framework.utility', $framework);
        $container->setParameter('application.path', dirname($configFile));
        $container->setParameter('application.config.file', $configFile);
        $container->addScope(new Scope('application'));
        if (!$container->hasScope('request')) {
            $container->addScope(new Scope('request', 'application'));
        }

        return new self($container);
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

    /**
     * Returns the container
     *
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }


    /**
     * Run an app based on environment variables
     *
     * @param string $configFile an application configuration file
     * @param array  $server     server variable typically $_SERVER
     * @param array  $get        request parameters typically $_GET
     * @param array  $post       request post parameters typically $_POST
     * @param array  $files      request files typically $_FILES
     * @param array  $session    session variables typically $_SESSION
     * @param array  $cookie     cookie parameters typically $_COOKIE
     * @param array  $env        environment variables typically $_ENV
     */
    public static function runApp(
        $configFile, $server, $get, $post, $files, $session,
        $cookie, $env
    )
    {
        $appLoader = self::getAppLoader($configFile);
        $container = $appLoader->getContainer();
        $responseExporter = $container->get('asar.responseExporter');
        $requestFactory = $container->get('asar.requestFactory');

        $app = $appLoader->loadApplication();
        $responseExporter->exportResponse(
            $app->handleRequest(
                $requestFactory->createRequestFromEnvironment(
                    $server, $get, $post, $files, $session, $cookie, $env
                )
            )
        );
    }

}
