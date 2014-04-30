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

use Asar\TestHelper\TestCase;
use Asar\Routing\Route;
use Asar\Template\Engine\EngineRegistry;
use Asar\Template\TemplateAssembler;

/**
 * Specifications for TemplateAssembler
 */
class TemplateAssemblerTest extends TestCase
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
        $this->registry = new EngineRegistry;
        // Register a php template Engine
        $this->registry->register(
            'php', $this->quickMock('Asar\Template\Engine\EngineInterface')
        );
        $this->appPath = '/foo/Namespace';
        $this->resourceName = 'FooResource';
        $this->templateFinder = new TemplateAssembler(
            $this->appPath, $this->registry, $this->fileSystemUtility
        );
    }

    private function commonFindingTemplate(array $testParameters)
    {
        $this->fileSystemUtility->expects($this->once())
            ->method('findFilesThatStartWith')
            ->with($testParameters['filePrefix'])
            ->will($this->returnValue(array($testParameters['filePrefix'] . '.php')));
        $this->templateFinder->find($testParameters['resourceName'], $testParameters['options']);
    }

    /**
     * Finds template file based on resource name, method, and prefix
     */
    public function testFindsFilesBasedOnResourceName()
    {
        $this->commonFindingTemplate(array(
            'resourceName' => $this->resourceName,
            'options'      => array('type' => 'html', 'method' => 'GET'),
            'filePrefix'   => '/foo/Namespace/Representation/FooResource.GET.html'
        ));
    }

    /**
     * Finds template file based on resource name, method, <dot>, and prefix
     */
    public function testMatchesFilenamesBasedOnCorrectDirectorySyntax()
    {
        $this->commonFindingTemplate(array(
            'resourceName' => 'Foo\Resource',
            'options'      => array('type' => 'html', 'method' => 'GET'),
            'filePrefix'   => '/foo/Namespace/Representation/Foo/Resource.GET.html'
        ));
    }

    /**
     * Finds template files with different options
     */
    public function testFindsFilesWithDifferentOptions()
    {
        $this->commonFindingTemplate(array(
            'resourceName' => $this->resourceName,
            'options'      => array('type' => 'xml', 'method' => 'POST'),
            'filePrefix'   => '/foo/Namespace/Representation/FooResource.POST.xml'
        ));
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

    /**
     * Matches files with those on registry
     */
    public function testMatchesFilesOnRegistry()
    {
        $options = array('type' => 'xml', 'method' => 'POST');
        $filePrefix = '/foo/Namespace/Representation/FooResource.POST.xml';
        $foundFiles = array($filePrefix . '.php', $filePrefix . '.foo');
        $this->fileSystemUtility->expects($this->once())
            ->method('findFilesThatStartWith')
            ->with($filePrefix)
            ->will($this->returnValue($foundFiles));
        $this->registry->register('foo', $engine = $this->quickMock('Asar\Template\Engine\EngineInterface'));
        $template = $this->templateFinder->find($this->resourceName, $options);
        $this->assertEquals($engine, $template->getEngine());
    }

    /**
     * Throws exception when no matching template engine is found
     */
    public function testThrowsExceptionWhenThereIsNoMatchingEngine()
    {
        $options = array('type' => 'xml', 'method' => 'POST');
        $filePrefix = '/foo/Namespace/Representation/FooResource.POST.xml';
        $foundFiles = array($filePrefix . '.foo', $filePrefix . '.bar');
        $this->setExpectedException(
            'Asar\Template\Exception\EngineNotFound',
            "There was no registered engines matched "
        );
        $this->fileSystemUtility->expects($this->once())
            ->method('findFilesThatStartWith')
            ->with($filePrefix)
            ->will($this->returnValue($foundFiles));
        $this->templateFinder->find($this->resourceName, $options);
    }


}
