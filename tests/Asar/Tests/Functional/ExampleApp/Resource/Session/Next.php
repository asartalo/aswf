<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Functional\ExampleApp\Resource\Session;

use Asar\Http\Resource\GetInterface;
use Asar\Http\Message\Request;
use Asar\Session\StoreInterface as SessionStore;
use Asar\Content\Page;

/**
 * Another page for session testing
 */
class Next implements GetInterface
{
    private $page;
    private $session;

    /**
     * @param Page         $page    a page object
     * @param SessionStore $session a session object
     *
     * @inject request.page
     * @inject session.store
     */
    public function __construct(Page $page, SessionStore $session)
    {
        $this->page = $page;
        $this->session = $session;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function GET(Request $request)
    {
        $random = '';
        if ($this->session->has('random')) {
            $random = $this->session->get('random');
        }
        $this->page->set(array(
            'title' => "Session Testing Start",
            'random' => $random
        ));

        return $this->page->getResponse();
    }



}