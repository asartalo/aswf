<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Template;

use Asar\Template\Engine\EngineInterface;

/**
 * Assembles templates and template engine for rendering
 */
class TemplateAssembly
{
    private $file;

    private $type;

    private $engine;

    /**
     * Constructor
     *
     * @param string          $file   the file path
     * @param string          $type   the template type
     * @param EngineInterface $engine the template engine
     */
    public function __construct($file, $type, EngineInterface $engine)
    {
        $this->file = $file;
        $this->type = $type;
        $this->engine = $engine;
    }

    /**
     * Gets the file
     *
     * @return string the template file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Gets the type
     *
     * @return string the template type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the template engine
     *
     * @return string the template engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Renders the template
     *
     * @param array $params the template parameters
     *
     * @return string the rendered template
     */
    public function render(array $params = array())
    {
        return $this->engine->render($this->file, $params);
    }
}