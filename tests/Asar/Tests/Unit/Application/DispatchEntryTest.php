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
use Asar\Application\DispatchEntry;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;
use Dimple\Container;

/**
 * Specifications for Asar\Application\DispatchEntry
 */
class DispatchEntryTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->container = $this->quickMock('Dimple\Container');
        $this->dispatchEntry = new DispatchEntry($this->container);
    }

    /**
     * Dispatch a request sets the scope to request
     */
    public function testDispatchSetsScopeToRequest()
    {
        $this->container->expects($this->once())
            ->method('enterScope')
            ->with('request');
        $this->dispatchEntry->dispatch(new Request);
    }

    /**
     * Dispatch sets the request as a service
     */
    public function testDipatchSetsRequestAsService()
    {
        $this->container->expects($this->at(1))
            ->method('offsetSet')
            ->with('request.request', $request = new Request);
        $this->dispatchEntry->dispatch($request);
    }

    /**
     * Dispatch asks for response from container
     */
    public function testDipatchAsksResponseFromContainer()
    {
        $this->container->expects($this->at(2))
            ->method('get')
            ->with('request.response');
        $this->dispatchEntry->dispatch(new Request);
    }

    /**
     * Dispatch returns response from container
     */
    public function testDispatchReturnsResponse()
    {
        $this->container->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response = new Response));
        $this->assertSame($response, $this->dispatchEntry->dispatch(new Request));
    }

    /**
     * Dispatch leaves request scope after getting response
     */
    public function testDispatchLeavesScopeRequestScopeAfterGettingResponse()
    {
        $this->container->expects($this->at(3))
            ->method('leaveScope')
            ->with('request');
        $this->dispatchEntry->dispatch(new Request);
    }


}