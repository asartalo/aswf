<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests;

use Asar\Tests\TempFilesManager;

/**
 * Specifications for Asar\Tests\TempFilesManager
 */
class TempFilesManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->tempdirParent = realpath(__DIR__ . '/../../') . '/data';
        $this->tempdir = $this->tempdirParent . DIRECTORY_SEPARATOR . 'temp';
        $this->tempFilesManager = new TempFilesManager($this->tempdir);
        $this->recursiveDelete($this->tempdir, false);
    }

    /**
     * Teardown
     */
    public function tearDown()
    {
        $this->recursiveDelete($this->tempdir, false);
    }

    private function recursiveDelete($folderPath, $thisToo = true)
    {
        if (file_exists($folderPath) && is_dir($folderPath)) {
            $contents = scandir($folderPath);
            foreach ($contents as $value) {
                if ($value != "." && $value != ".." && $value != '.svn') {
                    $value = $folderPath . "/" . $value;
                    if (is_dir($value)) {
                        $this->recursiveDelete($value);
                    } elseif (is_file($value)) {
                        @unlink($value);
                    }
                }
            }
            if ($thisToo) {
                return rmdir($folderPath);
            }
        } else {
             return false;
        }
    }

    /**
     * Test initialization
     */
    public function testInitialization()
    {
        $this->assertContains(
            realpath(__DIR__ . '/../../'), $this->tempdirParent
        );
        $this->assertFileExists($this->tempdirParent);
    }

    /**
     * Throws exception when temporary directory does not exist
     */
    public function testInstatiationThrowsErrorWhenTempDirDoesNotExist()
    {
        $dir = 'foo_dir';
        $this->setExpectedException(
            'Asar\Tests\TempFilesManagerException',
            "The directory specified ($dir) as temporary directory does not exist."
        );
        $tempFilesManager = new TempFilesManager($dir);
    }

    private function getFilePath($file)
    {
        return $this->tempdir . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * Adds files to temporary directory
     */
    public function testAddingFilesToTemp()
    {
        $this->tempFilesManager->newFile('foo.txt', 'bar');
        $fileFullPath = $this->getFilePath('foo.txt');
        $this->assertFileExists($fileFullPath);
        $this->assertEquals('bar', file_get_contents($fileFullPath));
    }

    /**
     * Adds files with directory paths
     */
    public function testAddingFilesWithDirectoryPaths()
    {
        $file = 'foo/bar/baz.txt';
        $this->tempFilesManager->newFile($file, 'foo bar baz');
        $this->assertFileExists($this->getFilePath($file));
        $this->assertEquals(
            'foo bar baz', file_get_contents($this->getFilePath($file))
        );
    }

    /**
     * Creates directories in temporary directory
     */
    public function testCreatingDirectories()
    {
        $dir = 'foo/bar/baz';
        $this->tempFilesManager->newDir($dir);
        $this->assertFileExists($this->getFilePath($dir));
    }

    /**
     * Can get full file path
     */
    public function testGettingFullFilePath()
    {
        $files = array('foo.txt' => 'foo', 'bar/baz.txt' => 'bar baz');
        foreach ($files as $file => $contents) {
            $this->tempFilesManager->newFile($file, $contents);
            $this->assertEquals(
                $this->getFilePath($file), $this->tempFilesManager->getPath($file)
            );
        }
    }

    /**
     * Can remove files in temporary directory
     */
    public function testRemovingFilesInTemp()
    {
        $this->tempFilesManager->newFile('file1', 'test');
        $this->tempFilesManager->removeFile('file1');
        $this->assertFileNotExists($this->getFilePath('file1'));
    }

    /**
     * Clears temporary directory
     *
     * @param array $files list of files to be created and deleted
     *
     * @dataProvider clearingTempDirTestData
     */
    public function testClearingTempDirectory($files)
    {
        foreach ($files as $file => $contents) {
            $this->tempFilesManager->newFile($file, $contents);
            $this->assertFileExists($this->getFilePath($file));
        }
        $this->tempFilesManager->clearTempDirectory();
        foreach (array_keys($files) as $file) {
            $this->assertFileNotExists($this->getFilePath($file));
        }
    }

    /**
     * A list of files to be created and deleted
     *
     * @return array a list of files
     */
    public function clearingTempDirTestData()
    {
        return array(
            array(
                array('file1' => 'test1', 'file2' => 'test2', 'file3' => 'test3')
            ),
            array(
                array(
                    'foo/file1' => 'test1',
                    'bar/file2' => 'test2',
                    'foo/baz/file3' => 'test3'
                )
            )
        );
    }

    /**
     * Can obtain temporary directory path
     */
    public function testGettingTempDirectory()
    {
        $this->assertEquals($this->tempdir, $this->tempFilesManager->getTempDirectory());
    }
}
