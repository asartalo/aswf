<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Template;

use Asar\Tests\TestCase;
use Asar\Routing\Route;
use Asar\Template\TemplateFinder;

/**
 * Specifications for TemplateFinder
 */
class TemplateFinderTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->fileSystemUtility = $this->getMock(
            'Asar\FileSystem\Utility',
            array('setCurrentDirectory', 'findFilesThatStartWith')
        );
        $this->appPath = '/foo/Namespace';
        $this->resourceName = 'FooResource';
        $this->templateFinder = new TemplateFinder(
            $this->appPath, $this->fileSystemUtility
        );
    }

    /**
     * Finds template files based on resource name
     */
    public function testFindsFilesBasedOnResourceName()
    {
        $filePrefix = '/foo/Namespace/Representation/FooResource.GET.html';
        $this->fileSystemUtility->expects($this->once())
            ->method('findFilesThatStartWith')
            ->with($filePrefix)
            ->will($this->returnValue($files = array($filePrefix . '.php')));
        $result = $this->templateFinder->find(
            $this->resourceName, array('type' => 'html', 'method' => 'GET')
        );
        $this->assertEquals($files, $result);
    }

    /**
     * Finds files but with empty options searches default values
     */
    public function testFindsFilesButWithEmptyOptionsSearchesWithDefaults()
    {
        $options = array();
        $filePrefix = '/foo/Namespace/Representation/FooResource.GET.html';
        $this->fileSystemUtility->expects($this->once())
            ->method('findFilesThatStartWith')
            ->with($filePrefix)
            ->will($this->returnValue($files = array($filePrefix . '.php')));
        $result = $this->templateFinder->find($this->resourceName, $options);
        $this->assertEquals($files, $result);
    }

    /**
     * Finds template files with different options
     */
    public function testFindsFilesWithDifferentOptions()
    {
        $options = array('type' => 'xml', 'method' => 'POST');
        $filePrefix = '/foo/Namespace/Representation/FooResource.POST.xml';
        $this->fileSystemUtility->expects($this->once())
            ->method('findFilesThatStartWith')
            ->with($filePrefix)
            ->will($this->returnValue($files = array($filePrefix . '.php')));
        $result = $this->templateFinder->find(
            $this->resourceName, $options
        );
        $this->assertEquals($files, $result);
    }

    /**
     * Throws exception when no template is found
     */
    public function testThrowsExceptionWhenNoTemplateIsFound()
    {
        $this->setExpectedException(
            'Asar\Template\Exception\TemplateFileNotFound',
            "No template file found in '/foo/Namespace/Representation/' for resource 'FooResource' with method 'PUT' and type 'json'."
        );
        $this->fileSystemUtility->expects($this->once())
            ->method('findFilesThatStartWith')
            ->will($this->returnValue(array()));
        $this->templateFinder->find($this->resourceName, array('method' => 'PUT', 'type' => 'json'));
    }


}
