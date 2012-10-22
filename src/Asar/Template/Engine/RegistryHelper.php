<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Template\Engine;

use Asar\Template\Engine\EngineRegistry;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Registers template engines
 */
class RegistryHelper
{

    private $registry;

    private $container;

    /**
     * Constructor
     *
     * @param EngineRgistry      $registry  template engine registry
     * @param ContainerInterface $container the dependency injection container
     */
    public function __construct(EngineRegistry $registry, ContainerInterface $container)
    {
        $this->registry = $registry;
        $this->container = $container;
    }

    /**
     * Obtains engines and registers them
     *
     * @param array $engines map of types and engines
     *
     * @return EngineRegistry registry with registered engines
     */
    public function registerEngines($engines = array())
    {
        foreach ($engines as $type => $engineService) {
            $engine = $this->container->get($engineService);
            $this->registry->register($type, $engine);
        }

        return $this->registry;
    }

}