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

/**
 * Renders templates
 */
interface EngineInterface
{

    /**
     * Renders the template
     *
     * @param string $file   the template file path
     * @param array  $params the template parameters
     *
     * @return string the rendered template
     */
    public function render($file, $params = array());
}