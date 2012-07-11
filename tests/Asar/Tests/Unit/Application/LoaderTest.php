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

use Asar\Application\Loader;
use Asar\Application\Application;
use Asar\Tests\TestCase;

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
        $this->config = array(
            'routes' => array(
                'root' => array(
                    'resourceName' => 'RootResource'
                )
            )
        );
    }

    /**
     * Test loading an application from a configuration
     */
    public function testLoadsAnApplication()
    {
        $this->markTestIncomplete();
        $this->assertInstanceOf(
            'Asar\Application\Application',
            Loader::load('')
        );
    }

    /**
     * Test instantiating an application
     */
    public function testInstantiateAnApplication()
    {
        $loader = new Loader($this->config);
        $this->markTestIncomplete();
    }

}