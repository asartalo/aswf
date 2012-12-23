<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Utility;

use Asar\TestHelper\TestCase;
use Asar\Utilities\Framework;

/**
 * Specification for the Framework utility class
 */
class FrameworkTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->frameworkSourcePath = realpath(__DIR__ . '/../../../../../') . DIRECTORY_SEPARATOR . 'src';
        $this->framework = new Framework;
    }

    /**
     * Get framework source directory
     */
    public function testGetFrameworkSourceDirectory()
    {
        $this->assertEquals(
            $this->frameworkSourcePath,
            $this->framework->getSourcePath()
        );
    }

    /**
     * Get a resource path
     */
    public function testGettingResource()
    {
        $this->assertEquals(
            implode(
                DIRECTORY_SEPARATOR,
                array($this->frameworkSourcePath, 'Asar', 'Resources', 'config.default.yml')
            ),
            $this->framework->getResourcePath('config.default.yml')
        );
    }

    /**
     * Get the resources directory
     */
    public function testGettingResourcesDirectory()
    {
        $this->assertEquals(
            implode(
                DIRECTORY_SEPARATOR,
                array($this->frameworkSourcePath, 'Asar', 'Resources')
            ),
            $this->framework->getResourcesPath()
        );
    }

}