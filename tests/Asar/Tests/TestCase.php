<?php

namespace Asar\Tests;

/**
 * A helper class to wrap common test setups in one class for easier testing.
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase {

  protected function quickMock($class, array $methods = array())
  {
    return $this->getMock($class, $methods, array(), '', false);
  }

  protected function getTempDir()
  {
    return \Asar::getInstance()->getFrameworkTestsDataTempPath();
  }

  function getTFM()
  {
    if (!isset($this->_TFM)) {
      $this->_TFM = new TempFilesManager($this->getTempDir());
    }

    return $this->_TFM;
  }

  protected function clearTestTempDirectory()
  {
    $this->getTFM()->clearTempDirectory();
  }

  protected function generateAppName($last)
  {
    return str_replace('\\', '_', get_class($this)) . $last;
  }

  protected function generateAppNameNew($last)
  {
    return str_replace('_', '\\', get_class($this) . $last);
  }

  protected function generateUnderscoredName($name)
  {
    return str_replace('\\', '_', $name);
  }

  protected function createClassDefinitionStr(
    $full_classname, $extends = '', $body = ''
  ) {
    $splitname = $this->splitFullClassName($full_classname);
    $extends = $extends ? " extends $extends" : '';

    return "
      namespace {$splitname['namespace']} {
        class {$splitname['class']}$extends {
          $body
        }
      }
    ";
  }

  protected function createClassDefinition(
    $full_classname, $extends = '', $body = ''
  ) {
    $class_def = $this->createClassDefinitionStr(
      $full_classname, $extends, $body
    );
    eval ($class_def);
  }

  protected function splitFullClassName($full_classname)
  {
    $all = explode('\\', $full_classname);
    $return['class'] = array_pop($all);
    $return['namespace'] = implode('\\', $all);

    return $return;
  }

}
