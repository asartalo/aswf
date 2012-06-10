<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Utilities;

/**
 * A collection of utility methods to deal with strings
 */
class String
{
    /**
     * Converts a string to a dash camel case
     *
     * For example, "foo Bar" is converted to "Foo-Bar".
     *
     * @param string $string
     *
     * @return string string in dash camel case form
     */
    public static function dashCamelCase($string)
    {
        return str_replace(' ', '-', self::ucwordsLower($string));
    }


    /**
     * Converts a string to a dash lower case
     *
     * For example, "foo Bar" is converted to "foo-bar".
     *
     * @param string $string
     *
     * @return string string in dash lower case form
     */
    public static function dashLowerCase($string)
    {
        return self::uncamelize($string, '-');
    }

    private static function uncamelize($string, $splitter="_")
    {
        $string = preg_replace('/[[:upper:]]/', $splitter.'$0', $string);

        return trim(strtolower($string), '-');
    }

    /**
     * Converts a string to a CamelCase form
     *
     * For example, "foo-bar" is converted to "FooBar".
     *
     * @param string $string
     *
     * @return string string in camel case form
     */
    public static function camelCase($string)
    {
        return str_replace(array(' ', '-'), '', self::ucwordsLower($string));
    }


    /**
     * Converts a string to a underscored form
     *
     * For example, "fooBar" is converted to "foo_bar".
     *
     * @param string $string
     *
     * @return string string in underscore form
     */
    public static function underScore($string)
    {
        return str_replace(array(' ', '-'), '_', strtolower($string));
    }

    private static function ucwordsLower($string)
    {
        return ucwords(
            strtolower(str_replace(array('-', '_'), ' ', $string))
        );
    }


    /**
     * Tests if a string starts with the prefix
     *
     * @param string $string
     * @param string $prefix
     *
     * @return boolean wether $string starts with $prefix
     */
    public static function startsWith($string, $prefix)
    {
        return strpos($string, $prefix) === 0;
    }

}
