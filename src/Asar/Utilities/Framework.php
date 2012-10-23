<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Utilities;

/**
 * Utility for retrieving information about the framework
 */
class Framework
{

    private $sourcePath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sourcePath = realpath(__DIR__ . '/../../');
    }

    /**
     * Get the framework source path
     *
     * @return string the framework's source path
     */
    public function getSourcePath()
    {
        return $this->sourcePath;
    }

    /**
     * Get a resource file's path
     *
     * @param string $file the file name of the resource
     *
     * @return string the path to the resouce path
     */
    public function getResourcePath($file)
    {
        return $this->createPath($this->getResourcesPath(), $file);
    }

    /**
     * Retrieve the Resources directory where resources are located
     *
     * @return string the path to the resources directory
     */
    public function getResourcesPath()
    {
        return $this->createPath($this->sourcePath, 'Asar', 'Resources');
    }

    private function createPath()
    {
        $arguments = func_get_args();

        return implode(DIRECTORY_SEPARATOR, $arguments);
    }

}