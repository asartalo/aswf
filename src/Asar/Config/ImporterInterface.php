<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Config;

use Asar\FileSystem\File;

/**
 * Imports a supported file's data for use in configurations
 */
interface ImporterInterface
{

    /**
     * @return string the supported file type (e.g. 'xml' for '.xml' files)
     */
    public function type();

    /**
     * @param File $config the config file to import
     *
     * @return array the raw config data imported from the supported file
     */
    public function import(File $config);

}