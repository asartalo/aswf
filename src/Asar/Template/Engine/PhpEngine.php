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

/**
 * A simple PHP-based template engine
 */
class PhpEngine implements EngineInterface
{
    private $file;

    private $templateParams = array();

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

}