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
 * Session starts here
 */
class Start implements GetInterface
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
        if (!$this->session->has('random')) {
            $this->session->set('random', $this->generateRandomString());
        }
        $this->page->set(array(
            'title' => "Session Testing Start",
            'random' => $this->session->get('random')
        ));

        return $this->page->getResponse();
    }

    /**
     * Generates a random string
     *
     * @return String
     */
    private function generateRandomString()
    {
        return substr(md5(rand()), 0, 20);
    }



}
