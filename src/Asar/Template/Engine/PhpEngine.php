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
        $template = new PhpEngineObject($this, $file, $this->helpers, $params);
        $output = $template->render();
        if ($template->hasLayout()) {
            $output = $this->render(
                $template->getLayout(),
                array_merge(
                    $params,
                    $template->getLayoutParameters(),
                    array('content' => $output)
                )
            );
        }

        return $output;
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

}
