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

use Symfony\Component\Yaml\Parser;

/**
 * Imports yaml file configurations
 */
class YamlImporter implements ImporterInterface
{

    /**
     * @param Parser $parser Yaml parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Supports yaml types
     *
     * @return string
     */
    public function type()
    {
        return 'yml';
    }

    /**
     * Imports a yaml configuration file
     *
     * @param string $config the yaml config file
     *
     * @return array config data
     */
    public function import($config)
    {
        return $this->parser->parse($config);
    }

}