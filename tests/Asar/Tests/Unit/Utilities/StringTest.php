<?php

namespace Asar\Tests\Unit\Utility;

use \Asar\Utilities\String;

class StringTest extends \Asar\Tests\TestCase {

  private function _testFunction($method, $test_data) {
    $failmsg = "Asar\Utilities\String::$method() did not return expected string.";
    foreach($test_data as $orig => $converted) {
      $this->assertEquals(
        $converted,
        call_user_func(array('Asar\Utilities\String', $method), $orig),
        $failmsg
      );
    }
  }

  function testDashCamelCase() {
    $tests = array(
      'wanton-noodles' => 'Wanton-Noodles',
      'HOLy maCaroni'  => 'Holy-Macaroni',
      'bAd'            => 'Bad',
      'foo'            => 'Foo',
      'BAR'            => 'Bar'
    );
    $this->_testFunction('dashCamelCase', $tests);
  }

  function testDashLowerCase() {
    $tests = array(
      'FooBar'         => 'foo-bar',
      'wanton-noodles' => 'wanton-noodles',
      'HOLy-maCaroni'  => 'h-o-ly-ma-caroni',
      'bAd'            => 'b-ad',
      'foo'            => 'foo',
      'BAR'            => 'b-a-r',
    );
    $this->_testFunction('dashLowerCase', $tests);
  }

  function testCamelCase() {
    $tests = array(
      'wanton-noodles' => 'WantonNoodles',
      'HOLy-maCaroni'  => 'HolyMacaroni',
      'camel case'     => 'CamelCase',
      'very_GoOd-food' => 'VeryGoodFood',
      'bAd'            => 'Bad',
      'foo'            => 'Foo',
      'BAR'            => 'Bar'
    );
    $this->_testFunction('camelCase', $tests);
  }

  function testStartsWith() {
    $this->assertSame(true, String::startsWith('Rararara', 'R'));
    $this->assertSame(true, String::startsWith('Wararara', 'War'));
    $this->assertSame(false, String::startsWith('Rararara', 'ar'));
  }

  function testUnderScore() {
    $tests = array(
      'wanton-noodles' => 'wanton_noodles',
      'HOLy-maCaroni'  => 'holy_macaroni',
      'camel case'     => 'camel_case',
      'very_GoOd-food' => 'very_good_food',
      'bAd'            => 'bad',
      'foo'            => 'foo',
      'BAR Roo'        => 'bar_roo'
    );
    $this->_testFunction('underScore', $tests);
  }
}
