<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Http\Resource;

use Asar\TestHelper\TestCase;
use Asar\Http\Resource\ResourceResolver;
use Asar\Config\Config;
use Asar\Routing\Route;

/** * Specifications for Asar\Http\Resource\ResourceFactory
 */
class ResourceResolverTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        if (!class_exists('ExampleApp\Resource\FooResource')) {
            $this->createClassDefinition('ExampleApp\Resource\FooResource');
        }
        $this->appPath = '/path/to/application';
        $this->config  = new Config(
            array('name' => 'ExampleApp', 'namespace' => 'ExampleApp')
        );
        $this->resolver = new ResourceResolver(
            $this->appPath, $this->config
        );
    }

    /**
     * Returns resource class name if it exists
     */
    public function testReturnsResourceClassNameIfItExists()
    {
        $this->assertEquals(
            'ExampleApp\Resource\FooResource',
            $this->resolver->getResourceClassName(
                new Route('/', 'FooResource', array())
            )
        );
    }

}