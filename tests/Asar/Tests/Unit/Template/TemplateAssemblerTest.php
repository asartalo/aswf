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
        $this->templateLocator = $this->quickMock(
            'Asar\Template\TemplateLocator',
            array('find')
        );
        $this->registry = new EngineRegistry;
        // Register a php template Engine
        $this->registry->register(
            'php', $this->quickMock('Asar\Template\Engine\EngineInterface')
        );
        $this->appPath = '/foo/Namespace';
        $this->resourceName = 'FooResource';
        $this->templateFinder = new TemplateAssembler(
            $this->appPath, $this->registry, $this->templateLocator
        );
    }

    private function commonFindingTemplate(array $testParameters)
    {
        $this->templateLocator->expects($this->atLeastOnce())
            ->method('find')
            ->with($testParameters['resourceName'], $testParameters['options'])
            ->will($this->returnValue($testParameters['foundFiles']));
        return $this->templateFinder->find(
            $testParameters['resourceName'], $testParameters['options']
        );
    }

    private function checkGeneratedTemplateAssembly()
    {
        return $this->commonFindingTemplate(array(
            'resourceName' => $this->resourceName,
            'options'      => array('type' => 'html', 'method' => 'GET', 'status' => 200),
            'foundFiles'   => array('FooResource.GET.html.haml', 'FooResource.GET.html.php')
        ));
    }

    /**
     * Creates TemplateAssembly based on matching template file
     */
    public function testCreatesTemplateAssemblyBasedOnResourceName()
    {
        $templateAssembly = $this->checkGeneratedTemplateAssembly();
        $this->assertEquals('FooResource.GET.html.php', $templateAssembly->getFile());
    }

    /**
     * Creates TemplateAssembly with matched engine
     */
    public function testCreatesTemplateAssemblyWithMatchingEngine()
    {
        $templateAssembly = $this->checkGeneratedTemplateAssembly();
        $this->assertEquals($this->registry->getEngine('php'), $templateAssembly->getEngine());
    }

    /**
     * Creates TemplateAssembly with matching type
     */
    public function testCreatesTemplateAssemblyWithMatchingType()
    {
        $templateAssembly = $this->checkGeneratedTemplateAssembly();
        $this->assertEquals('php', $templateAssembly->getType());
    }

    /**
     * Finds template files with different options
     */
    public function testFindsFilesWithDifferentOptions()
    {
        $assembly = $this->commonFindingTemplate(array(
            'resourceName' => $this->resourceName,
            'options'      => array('type' => 'xml', 'method' => 'POST', 'status' => 201),
            'foundFiles'   => array('FooResource.POST.xml.php')
        ));
        $this->assertEquals('FooResource.POST.xml.php', $assembly->getFile());
    }

    /**
     * Finds template files with matching status code
     */
    public function testFindsFilesWithMatchingStatusCode()
    {
        $assembly = $this->commonFindingTemplate(array(
            'resourceName' => $this->resourceName,
            'options'      => array('type' => 'html', 'method' => 'GET', 'status' => 200),
            'foundFiles'   => array('FooResource.200.html.php')
        ));
        $this->assertEquals('FooResource.200.html.php', $assembly->getFile());
    }

    /**
     * Finding template files prioritizes status code
     */
    public function testFindingFilesPrioritizesStatusCodeOverMethodTemplate()
    {
        $assembly = $this->commonFindingTemplate(array(
            'resourceName' => $this->resourceName,
            'options'      => array('type' => 'html', 'method' => 'GET', 'status' => 200),
            'foundFiles'   => array('FooResource.200.html.php', 'FooResource.GET.html.php')
        ));
        $this->assertEquals('FooResource.200.html.php', $assembly->getFile());
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
        $this->commonFindingTemplate(array(
            'resourceName' => $this->resourceName,
            'options'      => array('type' => 'json', 'method' => 'PUT', 'status' => 200),
            'foundFiles'   => array()
        ));
    }

    /**
     * Matches files with those with engine on registry
     */
    public function testMatchesFilesWithEngineOnRegistry()
    {
        $filePrefix = '/foo/Namespace/Representation/FooResource.POST.xml';
        $foundFiles = array($filePrefix . '.php', $filePrefix . '.foo');
        $engine = $this->quickMock('Asar\Template\Engine\EngineInterface');

        $this->registry->register('foo', $engine);

        $assembly = $this->commonFindingTemplate(array(
            'resourceName' => $this->resourceName,
            'options'      => array('type' => 'xml', 'method' => 'POST', 'status' => 200),
            'foundFiles'   => $foundFiles
        ));
        $this->assertEquals($engine, $assembly->getEngine());
    }

    /**
     * Throws exception when no matching template engine is found
     */
    public function testThrowsExceptionWhenThereIsNoMatchingEngine()
    {
        $filePrefix = '/foo/Namespace/Representation/FooResource.POST.xml';
        $foundFiles = array($filePrefix . '.foo', $filePrefix . '.bar');
        $this->setExpectedException(
            'Asar\Template\Exception\EngineNotFound',
            "There was no registered engines matched "
        );
        $assembly = $this->commonFindingTemplate(array(
            'resourceName' => $this->resourceName,
            'options'      => array('type' => 'xml', 'method' => 'POST', 'status' => 200),
            'foundFiles'   => $foundFiles
        ));
    }


}
