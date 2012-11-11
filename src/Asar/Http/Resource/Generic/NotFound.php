<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Http\Resource\Generic;

use Asar\Http\Resource\StandardResource;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;

/**
 * A generic resource for unknown resources (404)
 */
class NotFound extends StandardResource
{

    private function getResponse()
    {
        return new Response(array('status' => 404));
    }

    /**
     * @param Asar\Http\Message\Request $request
     */
    public function GET(Request $request)
    {
        return $this->getResponse();
    }

    /**
     * @param Asar\Http\Message\Request $request
     */
    public function POST(Request $request)
    {
        return $this->getResponse();
    }

    /**
     * @param Request $request
     */
    public function PUT(Request $request)
    {
        return $this->getResponse();
    }

    /**
     * @param Asar\Http\Message\Request $request
     */
    public function DELETE(Request $request)
    {
        return $this->getResponse();
    }

}