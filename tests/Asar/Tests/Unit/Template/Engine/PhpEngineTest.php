<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Template\Engine;

use Asar\Tests\TestCase;
use Asar\Template\Engine\PhpEngine;

/**
 * Specifications for PhpEngine
 */
class PhpEngineTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->tempFileManager = $this->getTFM();
        $this->clearTestTempDirectory();
        $this->engine = new PhpEngine;
    }

    /**
     * Teardown
     */
    public function tearDown()
    {
        $this->clearTestTempDirectory();
    }

    /**
     * Engine uses template file
     */
    public function testEngineUsesTemplateFile()
    {
        $this->tempFileManager->newFile('foo.php', 'Hello!');
        $this->assertEquals('Hello!', $this->engine->render($this->tempFileManager->getPath('foo.php')));
    }

    /**
     * Engine uses template variables
     */
    public function testEngineUsingPassedVariables()
    {
        $this->tempFileManager->newFile('inc.php', '<p><?php echo $var ?></p>');
        $this->assertEquals(
            '<p>Hello World!</p>',
            $this->engine->render(
                $this->tempFileManager->getPath('inc.php'),
                array('var' => 'Hello World!')
            )
        );
    }

    /**
     * Throws exception when template file does not exist
     */
    public function testThrowsExceptionWhenSettingFileThatDoesNotExist()
    {
        $this->setExpectedException(
            'Asar\Template\Engine\Exception\TemplateFileNotFound',
            "The file 'missing.php' passed to the template engine does not exist."
        );
        $this->engine->render('missing.php');
    }

}