<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Template\Helper;

use Asar\Template\Engine\EngineInterface;

/**
 * A helper that allows templates to extend other templates
 */
class Output
{

    /**
     * Escapes text for safe html TEXT output
     *
     * @param string $string the string to escape
     *
     * @return string an escaped string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

}