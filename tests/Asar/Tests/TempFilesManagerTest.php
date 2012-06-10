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

use \Asar\Tests\TempFilesManager;

class TempFilesManagerTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->tempdir_parent = realpath(__DIR__ . '/../../') . '/data';
        $this->tempdir = $this->tempdir_parent . DIRECTORY_SEPARATOR . 'temp';
        $this->TFM = new TempFilesManager($this->tempdir);
        $this->recursiveDelete($this->tempdir, false);
    }

    public function tearDown()
    {
        $this->recursiveDelete($this->tempdir, false);
    }

    private function recursiveDelete($folderPath, $this_too = true)
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
            if ($this_too) {
                return rmdir($folderPath);
            }
        } else {
             return false;
        }
    }

    public function testInitializing()
    {
        $this->assertContains(
            realpath(__DIR__ . '/../../'), $this->tempdir_parent
        );
        $this->assertFileExists($this->tempdir_parent);
    }

    public function testInstatiationThrowsErrorWhenTempDirDoesNotExist()
    {
        $dir = 'foo_dir';
        $this->setExpectedException(
            'Asar\Tests\TempFilesManagerException',
            "The directory specified ($dir) as temporary directory does not exist."
        );
        $TFM = new TempFilesManager($dir);
    }

    private function getFilePath($file)
    {
        return $this->tempdir . DIRECTORY_SEPARATOR . $file;
    }

    public function testAddingFilesToTemp()
    {
        $this->TFM->newFile('foo.txt', 'bar');
        $file_full_path = $this->getFilePath('foo.txt');
        $this->assertFileExists($file_full_path);
        $this->assertEquals('bar', file_get_contents($file_full_path));
    }

    public function testAddingFilesWithDirectoryPaths()
    {
        $file = 'foo/bar/baz.txt';
        $this->TFM->newFile($file, 'foo bar baz');
        $this->assertFileExists($this->getFilePath($file));
        $this->assertEquals(
            'foo bar baz', file_get_contents($this->getFilePath($file))
        );
    }

    public function testCreatingDirectories()
    {
        $dir = 'foo/bar/baz';
        $this->TFM->newDir($dir);
        $this->assertFileExists($this->getFilePath($dir));
    }

    public function testGettingFullFilePath()
    {
        $files = array('foo.txt' => 'foo', 'bar/baz.txt' => 'bar baz');
        foreach ($files as $file => $contents) {
            $this->TFM->newFile($file, $contents);
            $this->assertEquals(
                $this->getFilePath($file), $this->TFM->getPath($file)
            );
        }
    }

    public function testRemovingFilesInTemp()
    {
        $this->TFM->newFile('file1', 'test');
        $this->TFM->removeFile('file1');
        $this->assertFileNotExists($this->getFilePath('file1'));
    }

    /**
     * @dataProvider clearingTempDirTestData
     */
    public function testClearingTempDirectory($files)
    {
        foreach ($files as $file => $contents) {
            $this->TFM->newFile($file, $contents);
            $this->assertFileExists($this->getFilePath($file));
        }
        $this->TFM->clearTempDirectory();
        foreach (array_keys($files) as $file) {
            $this->assertFileNotExists($this->getFilePath($file));
        }
    }

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

    public function testGettingTempDirectory()
    {
        $this->assertEquals($this->tempdir, $this->TFM->getTempDirectory());
    }
}
