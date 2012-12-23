<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Application;

use Asar\TestHelper\TestCase;
use Asar\Application\Loader;
use Asar\Config\Config;

/**
 * Application Loader tests
 */
class LoaderTest extends TestCase
{
    /**
     * Setup
     */
    public function setUp()
    {
        $this->configData = array(
            'routes' => array(
                'root' => array(
                    'classRef' => 'RootResource'
                )
            )
        );
        $this->config = new Config($this->configData);
        $this->container = $this->quickMock(
            'Dimple\Container'
        );
    }

    /**
     * Test instantiating an application
     */
    public function testInstantiateAnApplication()
    {
        $app = $this->quickMock('Asar\Application\Application');
        $this->container->expects($this->atLeastOnce())
            ->method('get')
            ->with('application')
            ->will($this->returnValue($app));

        $loader = new Loader($this->container);
        $this->assertInstanceOf(
            'Asar\Application\Application',
            $loader->loadApplication($this->config)
        );
    }

}