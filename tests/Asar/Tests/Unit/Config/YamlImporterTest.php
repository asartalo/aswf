<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Config;

use Asar\TestHelper\TestCase;
use Asar\Config\YamlImporter;
use Asar\FileSystem\File;

/**
 * Tests Asar\Config\YamlImporter
 */
class YamlImporterTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->parser = $this->quickMock(
            'Symfony\Component\Yaml\Parser', array('parse')
        );
        $this->importer = new YamlImporter($this->parser);
    }

    /**
     * Supports yaml types
     */
    public function testSupportsYamlTypes()
    {
        $this->assertEquals('yml', $this->importer->type());
    }

    /**
     * Uses YAML parser
     */
    public function testUsesYamlParser()
    {
        $file = new File;
        $file->write('foo');
        $this->parser->expects($this->once())
            ->method('parse')
            ->with('foo');
        $this->importer->import($file);
    }

    /**
     * Uses output from YAML parser
     */
    public function testUsesYamlParserOutput()
    {
        $data = array('foo' => 'bar');
        $this->parser->expects($this->once())
            ->method('parse')
            ->will($this->returnValue($data));
        $this->assertEquals($data, $this->importer->import(new File));
    }

}