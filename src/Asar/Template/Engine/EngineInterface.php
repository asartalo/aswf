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

interface EngineInterface
{
    /**
     * Sets the template file
     *
     * @param string $file the template file path
     */
    public function setTemplate($file);


    /**
     * Sets a template parameter
     *
     * @param string $key   the template parameter key
     * @param mixed  $value the template parameter value
     */
    public function set($key, $value);

    /**
     * Renders the template
     *
     * @return string the rendered template
     */
    public function render();
}