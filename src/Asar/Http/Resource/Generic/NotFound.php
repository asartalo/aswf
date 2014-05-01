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
use Asar\Content\Page;
use Asar\Http\Message\Request;
use Asar\Http\Message\Response;
use Asar\Template\Exception\TemplateFileNotFound;

/**
 * A generic resource for unknown resources (404)
 */
class NotFound extends StandardResource
{
    private $page;

    /**
     * @param Page $page a page object
     *
     * @inject request.page
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    private function getResponse()
    {
        try {
            $this->page->setStatus(404);
            $response = $this->page->getResponse();
            if ($response) {
                return $response;
            }
        } catch(TemplateFileNotFound $e) {
            // Do nothing
        }
        return new Response(array('status' => 404));
    }

    /**
     * Handle GET requests
     *
     * @param Request $request
     *
     * @return Response
     */
    public function GET(Request $request)
    {
        return $this->getResponse();
    }

    /**
     * Handle POST requests
     *
     * @param Request $request
     *
     * @return Response
     */
    public function POST(Request $request)
    {
        return $this->getResponse();
    }

    /**
     * Handle PUT requests
     *
     * @param Request $request
     *
     * @return Response
     */
    public function PUT(Request $request)
    {
        return $this->getResponse();
    }

    /**
     * Handle DELETE requests
     *
     * @param Request $request
     *
     * @return Response
     */
    public function DELETE(Request $request)
    {
        return $this->getResponse();
    }

}
