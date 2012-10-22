<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Application;

use Asar\Tests\TestCase;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;
use Asar\Application\Application;

/**
 * Specifications for Asar\Application\Application
 */
class ApplicationTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->dispatchEntry = $this->quickMock(
            'Asar\Application\DispatchEntry', array('dispatch')
        );
        $this->app = new Application($this->dispatchEntry);
    }

    /**
     * Passes request path to dispatchEntry
     */
    public function testPassesRequestPathToRouter()
    {
        $this->dispatchEntry->expects($this->once())
            ->method('dispatch')
            ->with($request = new Request(array('path' => '/foo')));
        $this->app->handleRequest($request);
    }

    /**
     * Returns response from dispatchEntry
     */
    public function testRetursResponseFromResourceFoundByRouter()
    {
        $this->dispatchEntry->expects($this->once())
            ->method('dispatch')
            ->will($this->returnValue($response = new Response));
        $this->assertEquals(
            $response, $this->app->handleRequest(new Request)
        );
    }


}