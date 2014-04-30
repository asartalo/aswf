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

use Asar\FileSystem\Utility as FsUtility;
use Asar\Routing\Route;
use Asar\Template\Engine\EngineRegistry;
use Asar\Template\Exception\TemplateFileNotFound;
use Asar\Template\Exception\EngineNotFound;
use Asar\Template\TemplateAssembly;

/**
 * Assembles templates
 */
class TemplateAssembler
{
    private $appPath;

    private $registry;

    private $fsUtility;

    /**
     * Constructor
     *
     * @param string         $appPath   the application path
     * @param EngineRegistry $registry  the template engine registry
     * @param FsUtility      $fsUtility file system utility
     */
    public function __construct($appPath, EngineRegistry $registry, FsUtility $fsUtility)
    {
        $this->appPath = $appPath;
        $this->registry = $registry;
        $this->fsUtility = $fsUtility;
    }

    /**
     * Find template file based on resource name, method and type
     *
     * @param string $resourceName the resource name
     * @param array  $options      template file options
     *
     * @return string the path to the template file
     */
    public function find($resourceName, array $options)
    {
        $method = $options['method'];
        $type = $options['type'];
        $templateRootPath = $this->appPath . '/Representation/';

        $result = $this->fsUtility->findFilesThatStartWith(
            $templateRootPath . $this->pathize($resourceName) .
            '.' . $method . '.' . $type
        );

        if (empty($result)) {
            throw new TemplateFileNotFound(
                "No template file found in '{$templateRootPath}' " .
                "for resource '$resourceName' with method '$method' and type '$type'."
            );
        }

        return $this->matchTemplate($result);
    }

    private function pathize($resourceName)
    {
        return str_replace('\\', '/', $resourceName);
    }

    private function matchTemplate($result)
    {
        foreach ($result as $file) {
            $templateType = pathinfo($file, PATHINFO_EXTENSION);
            if ($this->registry->hasEngine($templateType)) {
                return new TemplateAssembly($file, $templateType, $this->registry->getEngine($templateType));
            }
        }
        // We did not find any template
        $files = implode("', '", $result);
        throw new EngineNotFound(
            "There was no registered engines matched for '$files'"
        );
    }

}
