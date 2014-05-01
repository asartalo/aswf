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
use Asar\Template\TemplateLocator;

/**
 * Specifications for TemplateLocator
 */
class TemplateLocatorTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->fileSystemUtility = $this->getMock(
            'Asar\FileSystem\Utility', array('findFilesThatMatch')
        );
        $this->appPath = '/foo/Namespace';
        $this->resourceName = 'FooResource';
        $this->templateLocator = new TemplateLocator(
            $this->appPath, $this->fileSystemUtility
        );
    }

    private function commonFindingTemplate(array $testParameters)
    {
        $filePrefix = $testParameters['fileToSearch'];
        $this->fileSystemUtility->expects($this->once())
            ->method('findFilesThatMatch')
            ->with($filePrefix)
            ->will($this->returnValue($testParameters['foundFiles']));
        return $this->templateLocator->find($testParameters['resourceName'], $testParameters['options']);
    }

    /**
     * Finds template files based on resource name
     */
    public function testFindFilesBasedOnResourceName()
    {
        $expectedResult = array('FooResource.GET.html.php');
        $found = $this->commonFindingTemplate(array(
            'resourceName' => 'FooResource',
            'fileToSearch' => '/foo/Namespace/Representation/FooResource.{GET,200}.html.*',
            'foundFiles'   => $expectedResult,
            'options'      => array('type' => 'html', 'method' => 'GET', 'status' => 200)
        ));
        $this->assertEquals($found, $expectedResult);
    }

    /**
     * Sets proper template name when resource name is empty
     */
    public function testFindSetsProperTemplateNamePatternWhenResourceNameIsEmpty()
    {
        $this->commonFindingTemplate(array(
            'resourceName' => '',
            'fileToSearch' => '/foo/Namespace/Representation/{GET,404}.html.*',
            'foundFiles'   => array(),
            'options'      => array('type' => 'html', 'method' => 'GET', 'status' => 404)
        ));
    }


}


