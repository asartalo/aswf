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
    function setUp()
    {
        $this->TFM = $this->getTFM();
        $this->clearTestTempDirectory();
        $this->T = new PhpEngine;
    }

    /**
     * Teardown
     */
    function tearDown()
    {
        $this->clearTestTempDirectory();
    }

    /**
     * Engine uses template file
     */
    function testEngineUsesTemplateFile()
    {
        $this->TFM->newFile('foo.php', 'Hello!');
        $this->T->setTemplate($this->TFM->getPath('foo.php'));
        $this->assertEquals('Hello!', $this->T->render());
    }

    /**
     * Engine uses template variables
     */
    function testEngineUsingPassedVariables()
    {
        $this->TFM->newFile('inc.php', '<p><?php echo $var ?></p>');
        $this->T->setTemplate($this->TFM->getPath('inc.php'));
        $this->T->set('var', 'Hello World!');
        $this->assertEquals(
            '<p>Hello World!</p>',
            $this->T->render()
        );
    }

    /**
     * Can obtain template file
     */
    function testGettingTemplateFile()
    {
        $this->TFM->newFile('baz.php', '');
        $this->T->setTemplate($this->TFM->getPath('baz.php'));
        $this->assertEquals(
            $this->TFM->getPath('baz.php'),
            $this->T->getTemplateFile()
        );
    }

    /**
     * Throws exception when template file does not exist
     */
    function testThrowsExceptionWhenSettingFileThatDoesNotExist()
    {
        $this->setExpectedException(
            'Asar\Template\Engine\Exception\TemplateFileNotFound',
            "The file 'missing.php' passed to the template engine does not exist."
        );
        $this->T->setTemplate('missing.php');
    }

}