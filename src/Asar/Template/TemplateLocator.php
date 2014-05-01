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

/**
 * Finds template files
 *
 * TemplateLocator looks for files based on resource name, request method,
 * request type, and response status.
 */
class TemplateLocator
{
    private $appPath;

    private $fsUtility;

    /**
     * Constructor
     *
     * @param string         $appPath   the application path
     * @param FsUtility      $fsUtility file system utility
     */
    public function __construct($appPath, FsUtility $fsUtility)
    {
        $this->appPath = $appPath;
        $this->fsUtility = $fsUtility;
    }

    public function find($resourceName, $options)
    {
        return $this->fsUtility->findFilesThatMatch(
            $this->generatePattern($resourceName, $options)
        );
    }

    private function generatePattern($resourceName, $options)
    {
        $templateRootPath = $this->appPath . '/Representation/';
        $start = $templateRootPath . $this->pathize($resourceName);
        if ($start !== $templateRootPath) {
            $start .= '.';
        }
        return $start . "{{$options['method']},{$options['status']}}.{$options['type']}.*";
    }

    private function pathize($resourceName)
    {
        return str_replace('\\', '/', $resourceName);
    }

}
