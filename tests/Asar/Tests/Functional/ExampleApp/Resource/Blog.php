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
use Asar\Tests\Functional\ExampleApp\Model\Blog as BlogModel;

/**
 * The index resource (homepage) for ExampleApp
 */
class Blog implements GetInterface
{
    private $page;

    private $blog;

    /**
     * @param Page      $page a page object
     * @param BlogModel $blog a blog sample model
     */
    public function __construct(Page $page, BlogModel $blog)
    {
        $this->page = $page;
        $this->blog = $blog;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function GET(Request $request)
    {
        foreach ($this->blog->getContents() as $var => $value) {
            $this->page->set($var, $value);
        }

        return $this->page->getResponse();
    }

}