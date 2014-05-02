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
 * A container for template rendering action
 */
class PhpEngineObject
{
    private $template;

    private $params;

    private $engine;

    private $layout;

    private $layoutParams = array();

    private $helpers = array();

    /**
     * Constructor
     *
     * @param PhpEngine $engine   the template engine
     * @param string    $template the template file to use
     * @param array     $helpers  a collection of template helpers
     * @param array     $params   the template parameters
     */
    public function __construct(PhpEngine $engine, $template, $helpers, $params = array())
    {
        if (!file_exists($template)) {
            throw new TemplateFileNotFound(
                "The file '$template' passed to the template engine does not exist."
            );
        }
        $this->template = $template;
        $this->params = $params;
        $this->engine = $engine;
        $this->helpers = $helpers;
    }

    /**
     * Renders the template
     *
     * @param string $file   a partial template
     * @param array  $params partial template parameters
     *
     * @return string the rendered template
     */
    public function render($file = null, array $params = array())
    {
        if ($file) {
            return $this->renderPartial($file, $params);
        }

        extract($this->params);
        ob_start();
        include $this->template;
        $output = ob_get_clean();

        return $output;
    }

    protected function renderPartial($file, $params)
    {
        $filePath = dirname($this->template) . DIRECTORY_SEPARATOR . $file;
        $partial = new self(
            $this->engine, $filePath, $this->helpers, $params
        );

        return $partial->render();
    }

    /**
     * Sets a layout template
     *
     * @param string $template the template file to use as layout
     * @param array  $params   optional template parameters for the layout
     */
    protected function layout($template, $params = array())
    {
        $this->layout = $this->getFullTemplatePath($template);
        $this->layoutParams = $params;
    }

    /**
     * Returns the layout parameters
     *
     * @return array the layout template parameters
     */
    public function getLayoutParameters()
    {
        return $this->layoutParams;
    }

    /**
     * Checks to see if template has a layout
     *
     * @return boolean whether the template has a layout or not
     */
    public function hasLayout()
    {
        return isset($this->layout);
    }

    /**
     * Returns the layout
     *
     * @return string the layout for the template
     */
    public function getLayout()
    {
        return $this->layout;
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

    private function getFullTemplatePath($anotherTemplate)
    {
        $templateDirectory = dirname($this->template);

        return realpath($templateDirectory . DIRECTORY_SEPARATOR . $anotherTemplate);
    }

}
