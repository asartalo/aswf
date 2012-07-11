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

use Asar\Tests\TestCase;
use Asar\Config\Config;

/**
 * Tests Asar\Config\Config
 */
class ConfigTest extends TestCase
{

    /**
     * Test setup
     */
    public function setUp()
    {
        $this->rawConfigData = array(
            'name' => 'ExampleApp',
            'routes' => array(
                'root' => array(
                    'resourceName' => 'Index'
                )
            )
        );
        $this->config = new Config($this->rawConfigData);
    }

    /**
     * Test Getting the Raw Format
     */
    public function testGettingRaw()
    {
        $this->assertEquals(
            $this->rawConfigData,
            $this->config->getRaw()
        );
    }

    /**
     * Test getting the data
     */
    public function testGettingData()
    {
        $this->assertEquals(
            'ExampleApp', $this->config->get('name')
        );
    }

    /**
     * Test getting nested data
     */
    public function testGettingNestedData()
    {
        $this->assertEquals(
            'Index', $this->config->get('routes.root.resourceName')
        );
    }

    /**
     * Getting unknown param returns null
     */
    public function testGettingUnknownParameterReturnsNull()
    {
        $this->assertEquals(null, $this->config->get('foo'));
    }

    /**
     * Getting a nested data that does not exist returns null
     */
    public function testGettingUnknownNestedParameterReturnsNull()
    {
        $this->assertEquals(null, $this->config->get('foo.bar'));
    }

    /**
     * Configurations are immutable
     */
    public function testConfigurationsAreImmutable()
    {
        $raw = $this->config->getRaw();
        $raw['foo'] = 'bar';
        $this->assertEquals(null, $this->config->get('foo'));
    }

    /**
     * Configurations can import other configuration files using importer
     */
    public function testCanImportOtherConfigurations()
    {
        $importer = $this->quickMock(
            'Asar\Config\ImporterInterface'
        );
        $importer->expects($this->any())
            ->method('type')
            ->will($this->returnValue('foo'));
        $data = array('foo' => 'bar');
        $importer->expects($this->once())
            ->method('import')
            ->will($this->returnValue($data));
        $config = new Config('bar.foo', array($importer));
        $this->assertEquals($data, $config->getRaw());
    }

}