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

use Asar\Template\Engine\Exception\TemplateFileNotFound;
use Asar\Template\Engine\Exception\UnknownHelperMethod;

/**
 * A simple PHP-based template engine
 */
class PhpEngine implements EngineInterface
{

    private $helpers = array();

    /**
     * Renders the template
     *
     * @param string $file   the template file path
     * @param array  $params the template parameters
     *
     * @return string the rendered template
     */
    public function render($file, $params = array())
    {
        if (!file_exists($file)) {
            throw new TemplateFileNotFound(
                "The file '$file' passed to the template engine does not exist."
            );
        }
        extract($params);
        ob_start();
        include $file;

        return ob_get_clean();
    }

    /**
     * Adds a helper
     *
     * @param string   $helperName the method name for the method to be called
     * @param callback $callback   the method to be called
     */
    public function addHelper($helperName, $callback)
    {
        $this->helpers[$helperName] = $callback;
    }

    /**
     * Calls helpers when one the helper is defined
     *
     * @param string $method    the helper method name that was called
     * @param array  $arguments the arguments passed to the method
     *
     * @return mixed whatever the callback returns
     */
    public function __call($method, $arguments)
    {
        if (!isset($this->helpers[$method])) {
            throw new UnknownHelperMethod("The helper method '$method' is not defined.");
        }

        return call_user_func_array($this->helpers[$method], $arguments);
    }

}