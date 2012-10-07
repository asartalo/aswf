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
use Asar\Template\Exception\TemplateFileNotFound;

/**
 * Finds template files
 */
class TemplateFinder
{
    private $appPath;

    private $fsUtility;

    /**
     * Constructor
     */
    public function __construct($appPath, FsUtility $fsUtility)
    {
        $this->appPath = $appPath;
        $this->fsUtility = $fsUtility;
    }

    /**
     * Find template files based on resource name, method and type
     *
     * @param string $resourceName the resource name
     * @param array  $options      template file options
     *
     * @return array template files found
     */
    public function find($resourceName, array $options = array())
    {
        $method = isset($options['method']) ? $options['method'] : 'GET';
        $type = isset($options['type']) ? $type = $options['type'] : 'html';

        $result = $this->fsUtility->findFilesThatStartWith(
            $this->appPath . '/Representation/'. $resourceName . '.' .
            $method . '.' . $type
        );

        if (empty($result)) {
            throw new TemplateFileNotFound(
                "No template file found in '{$this->appPath}/Representation/' " .
                "for resource '$resourceName' with method '$method' and type '$type'."
            );
        }

        return $result;
    }

}