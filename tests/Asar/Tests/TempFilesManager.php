<?php

namespace Asar\Tests;

class TempFilesManager
{
  private $temp_dir;

  function __construct($dir)
  {
    if (!file_exists($dir)) {
      throw new TempFilesManagerException(
        "The directory specified ($dir) as temporary directory " .
        "does not exist."
      );
    }
    $this->temp_dir = $dir;
  }

  function getPath($file_name)
  {
    return $this->temp_dir . DIRECTORY_SEPARATOR . $file_name;
  }

  function getTempDirectory()
  {
    return $this->temp_dir;
  }

  function newFile($file_name, $contents)
  {
    if ($this->isfileNameInDirectory($file_name)) {
      $this->createDirectoriesFirst($file_name);
    }
    //echo "\n$file_name";
    $file = fopen($this->getPath($file_name), 'w+');
    fwrite($file, $contents);
    fclose($file);
  }

  function newDir($dir_name)
  {
    $this->createDirectoriesFirst($dir_name, true);
  }

  private function isFileNameInDirectory($file_name)
  {
    return strpos($file_name, '/') > -1;
  }

  private function createDirectoriesFirst($file_name, $include_tail = false)
  {
    $dirs = explode('/', $file_name);
    if (!$include_tail) {
      array_pop($dirs);
    }
    $prepend = $this->temp_dir;
    foreach ($dirs as $dir) {
      $prepend .= DIRECTORY_SEPARATOR . $dir;
      if (!file_exists($prepend)) {
        mkdir($prepend);
      }
    }
  }

  function removeFile($file_name)
  {
    unlink($this->getPath($file_name));
  }

  function clearTempDirectory()
  {
    $this->recursiveDelete($this->temp_dir, false);
  }

  private function recursiveDelete($directory, $this_too = true)
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
      if ($this_too) {
        return rmdir($directory);
      }
    } else {
       return false;
    }
  }
}
