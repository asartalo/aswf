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

    /**
     * Test allows helper methods to be added
     */
    public function testHelperMethodsCanBeAdded()
    {
        $this->engine->addHelper(
            'foo', array($this, 'makeUpperCase')
        );
        $this->tempFileManager->newFile('inc.php', '<?php echo $this->foo($var) ?>');
        $this->assertEquals(
            'BAR',
            $this->engine->render(
                $this->tempFileManager->getPath('inc.php'),
                array('var' => 'bar')
            )
        );
    }

    /**
     * Makes a string uppercase. Used for testing only.
     *
     * @param string $input input string
     *
     * @return string the input string in uppercase
     */
    public function makeUpperCase($input)
    {
        return strtoupper($input);
    }

    /**
     * Throws exception when a call to an undefined method or helper is called
     */
    public function testThrowsExceptionWhenCallingUndefinedHelper()
    {
        $this->setExpectedException(
            'Asar\Template\Engine\Exception\UnknownHelperMethod',
            "The helper method 'foo' is not defined."
        );
        $this->tempFileManager->newFile('inc.php', '<?php echo $this->foo() ?>');
        $this->engine->render($this->tempFileManager->getPath('inc.php'), array());
    }



}