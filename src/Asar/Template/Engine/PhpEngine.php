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
class PhpEngine
{
    private $file;

    /**
     * Sets the template file
     *
     * @param string $file the template file
     */
    public function setTemplateFile($file)
    {
        if (!file_exists($file)) {
            throw new TemplateFileNotFound(
                "The file '$file' passed to the template engine does not exist."
            );
        }
        $this->file = $file;
    }

    /**
     * Returns the template file
     *
     * @return string the template file
     */
    public function getTemplateFile()
    {
        return $this->file;
    }

    /**
     * Renders the template
     *
     * @param array $templateParams the template parameters
     */
    public function render(array $templateParams = array())
    {
        extract($templateParams);
        ob_start();
        include $this->file;

        return ob_get_clean();
    }

}