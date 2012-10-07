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

/**
 * Manages temporary files and directories used in testing
 */
class TempFilesManager
{
    private $tempDir;

    /**
     * @param string $dir the temporary files directory
     */
    public function __construct($dir)
    {
        if (!file_exists($dir)) {
            throw new TempFilesManagerException(
                "The directory specified ($dir) as temporary directory " .
                "does not exist."
            );
        }

        $this->tempDir = $dir;
    }

    /**
     * @param string $fileName
     *
     * @return string the full path to the file
     */
    public function getPath($fileName)
    {
        return $this->tempDir . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * @return string the temporary directory set
     */
    public function getTempDirectory()
    {
        return $this->tempDir;
    }

    /**
     * Creates a file with the filename and contents in the temp directory
     *
     * @param string $fileName the file name
     * @param string $contents the contents of the file
     */
    public function newFile($fileName, $contents = '')
    {
        $fileName = ltrim($fileName, '/\\');
        if ($this->isfileNameInDirectory($fileName)) {
            $this->createDirectoriesFirst($fileName);
        }
        $file = fopen($this->getPath($fileName), 'w+');
        fwrite($file, $contents);
        fclose($file);
    }


    /**
     * Creates a sub-directory in the temp directory
     *
     * @param string $dirName the name of the sub directory
     */
    public function newDir($dirName)
    {
        $this->createDirectoriesFirst($dirName, true);
    }

    private function isFileNameInDirectory($fileName)
    {
        return strpos($fileName, '/') > -1;
    }

    private function createDirectoriesFirst($fileName, $includeTail = false)
    {
        $dirs = explode('/', $fileName);
        if (!$includeTail) {
            array_pop($dirs);
        }
        $prepend = $this->tempDir;
        foreach ($dirs as $dir) {
            $prepend .= DIRECTORY_SEPARATOR . $dir;
            if (!file_exists($prepend)) {
                mkdir($prepend);
            }
        }
    }

    /**
     * Deletes a file in the temporary directory
     *
     * @param string $fileName
     */
    public function removeFile($fileName)
    {
        unlink($this->getPath($fileName));
    }

    /**
     * Removes all files and directories inside the temp directory
     */
    public function clearTempDirectory()
    {
        $this->recursiveDelete($this->tempDir, false);
    }

    private function recursiveDelete($directory, $thisToo = true)
    {
        if (file_exists($directory) && is_dir($directory)) {
            $contents = scandir($directory);
            foreach ($contents as $value) {
                if ($value == "." || $value == ".." || $value == '.svn') {
                    continue;
                } else {
                    $value = $directory . "/" . $value;
                    if (is_dir($value)) {
                        $this->recursiveDelete($value);
                    } elseif (is_file($value)) {
                        @unlink($value);
                    }
                }
            }
            if ($thisToo) {
                return rmdir($directory);
            }
        } else {
             return false;
        }
    }
}
