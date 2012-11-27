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
use Asar\Content\Page;

/**
 * The index resource (homepage) for ExampleApp
 */
class Blog implements GetInterface
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

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function GET(Request $request)
    {
        $this->page->set('title', "The Blog");

        return $this->page->getResponse();
    }

}