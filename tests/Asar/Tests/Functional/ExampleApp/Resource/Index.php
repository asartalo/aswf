<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Functional\ExampleApp\Resource;

use Asar\Http\Resource\GetInterface;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;

/**
 * The index resource (homepage) for ExampleApp
 */
class Index implements GetInterface
{

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function GET(Request $request)
    {
        return new Response(array("content" => "Hello World!"));
    }

}