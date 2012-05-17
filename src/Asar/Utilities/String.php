<?php
namespace Asar\Utilities;

class String {

  static function dashCamelCase($string) {
    return str_replace(' ', '-', self::ucwordsLower($string));
  }

  static function dashLowerCase($string) {
    return self::uncamelize($string, '-');
  }

  private static function uncamelize($string, $splitter="_") {
    $string = preg_replace('/[[:upper:]]/', $splitter.'$0', $string);
    return trim(strtolower($string), '-');
  }

  static function camelCase($string) {
    return str_replace(array(' ', '-'), '', self::ucwordsLower($string));
  }

  static function underScore($string) {
    return str_replace(array(' ', '-'), '_', strtolower($string));
  }

  private static function ucwordsLower($string) {
    return ucwords(
      strtolower(str_replace(array('-', '_'), ' ', $string))
    );
  }

  static function startsWith($string, $prefix) {
    return strpos($string, $prefix) === 0;
  }

}
