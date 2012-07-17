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

use Asar\Config\ImporterInterface as Importer;
use Asar\FileSystem\File;

/**
 * A configuration
 *
 * Configurations are immutable - their values will not change once created.
 */
class Config
{
    private $data = array();

    private $importers = array();

    /**
     * @param mixed $config    the path to a configuration file or an array of
     *                         configuration data
     * @param array $importers a collection of config importers
     *                         that implement Asar\ConfigImporterInterface
     */
    public function __construct($config, $importers = array())
    {
        foreach ($importers as $importer) {
            $this->setupImporter($importer);
        }
        if (is_array($config)) {
            $this->data = $config;
        } else {
            $this->import($config);
        }
    }

    private function setupImporter(Importer $importer)
    {
        $this->importers[$importer->type()] = $importer;
    }

    private function import($config)
    {
        foreach ($this->importers as $type => $importer) {
            if (preg_match("/\.$type\$/", $config)) {
                $this->data = $importer->import(new File($config));
            }
        }
    }

    /**
     * Returns the raw configuration data
     *
     * @return array the data in array format
     */
    public function getRaw()
    {
        return $this->data;
    }

    /**
     * Returns the config data based on a key
     *
     * The key can be a simple alpha-numeric string or for nested values,
     * a path to the key with each will point to the value. For example:
     *
     * get('foo.bar.baz') is eqivalent to $config['foo']['bar']['baz']
     *
     * @param string $key the key to the configuration parameter
     *
     * @return mixed the value of the config parameter
     */
    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        $path = explode('.', $key);
        $value = $this->data;
        foreach ($path as $subpath) {
            if (isset($value[$subpath])) {
                $value = $value[$subpath];
            } else {
                return;
            }
        }

        return $value;
    }

}