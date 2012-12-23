<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Http\Resource\Generic;

use Asar\TestHelper\TestCase;
use Asar\Http\Resource\Generic\NotFound;
use Asar\Http\Message\Request;

/**
 * Specification for Asar\Http\Resource\Generic\NotFound
 */
class NotFoundTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->notFound = new NotFound;
    }

    /**
     * Always returns a 404 response status
     */
    public function testReturns404ResponseStatus()
    {
        $methods = array('GET', 'POST', 'PUT', 'DELETE');
        foreach ($methods as $method) {
            $request = new Request(array('method' => $method));
            $this->assertEquals(404, $this->notFound->$method($request)->getStatus());
        }
    }

}