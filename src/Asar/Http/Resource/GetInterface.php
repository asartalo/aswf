<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Http\Resource;

use Asar\Http\Message\Request;

/**
 * Can respond to GET requests
 */
interface GetInterface
{
    /**
     * @param Asar\Http\Message\Request $request
     */
    public function GET(Request $request);
}
