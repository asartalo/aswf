<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Utility;

use Asar\Utilities\String;
use Asar\Tests\TestCase;

/**
 * Specification for the String utility class
 */
class StringTest extends TestCase
{

    /**
     * Tests a string utility method
     *
     * @param string $method   the method to test
     * @param array  $testData an array of test data and their transformations
     */
    public function exerciseMethod($method, $testData)
    {
        $failmsg = "Asar\Utilities\String::$method() did not return expected string.";
        foreach ($testData as $orig => $converted) {
            $this->assertEquals(
                $converted,
                call_user_func(array('Asar\Utilities\String', $method), $orig),
                $failmsg
            );
        }
    }

    /**
     * Can Dash-Camel-Case strings
     */
    public function testDashCamelCase()
    {
        $tests = array(
            'wanton-noodles' => 'Wanton-Noodles',
            'HOLy maCaroni'  => 'Holy-Macaroni',
            'bAd'            => 'Bad',
            'foo'            => 'Foo',
            'BAR'            => 'Bar'
        );
        $this->exerciseMethod('dashCamelCase', $tests);
    }

    /**
     * Can dashLowerCase words
     */
    public function testDashLowerCase()
    {
        $tests = array(
            'FooBar'         => 'foo-bar',
            'wanton-noodles' => 'wanton-noodles',
            'HOLy-maCaroni'  => 'h-o-ly-ma-caroni',
            'bAd'            => 'b-ad',
            'foo'            => 'foo',
            'BAR'            => 'b-a-r',
        );
        $this->exerciseMethod('dashLowerCase', $tests);
    }

    /**
     * Can CamelCase words
     */
    public function testCamelCase()
    {
        $tests = array(
            'wanton-noodles' => 'WantonNoodles',
            'HOLy-maCaroni'  => 'HolyMacaroni',
            'camel case'     => 'CamelCase',
            'very_GoOd-food' => 'VeryGoodFood',
            'bAd'            => 'Bad',
            'foo'            => 'Foo',
            'BAR'            => 'Bar'
        );
        $this->exerciseMethod('camelCase', $tests);
    }

    /**
     * Can check to see if string starts with substring
     */
    public function testStartsWith()
    {
        $this->assertSame(true, String::startsWith('Rararara', 'R'));
        $this->assertSame(true, String::startsWith('Wararara', 'War'));
        $this->assertSame(false, String::startsWith('Rararara', 'ar'));
    }

    /**
     * Can under_score words
     */
    public function testUnderScore()
    {
        $tests = array(
            'wanton-noodles' => 'wanton_noodles',
            'HOLy-maCaroni'  => 'holy_macaroni',
            'camel case'     => 'camel_case',
            'very_GoOd-food' => 'very_good_food',
            'bAd'            => 'bad',
            'foo'            => 'foo',
            'BAR Roo'        => 'bar_roo'
        );
        $this->exerciseMethod('underScore', $tests);
    }
}
