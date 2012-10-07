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

use Asar\Template\Engine\Exception\UnregisteredEngine;

/**
 * A registry for template engines
 */
class EngineRegistry
{
    private $engines = array();

    /**
     * Registers a template engine
     *
     * @param string          $type   the registered type for the engine
     * @param EngineInterface $engine the template engine
     */
    public function register($type, EngineInterface $engine)
    {
        $this->engines[$type] = $engine;
    }

    /**
     * Returns a registered template engine based on type
     *
     * @param string $type the registered type for the engine
     *
     * @return EngineInterface the template engine
     */
    public function getEngine($type)
    {
        if (!isset($this->engines[$type])) {
            throw new UnregisteredEngine(
                "There is no template engine registered of type '$type'."
            );
        }

        return $this->engines[$type];
    }

}