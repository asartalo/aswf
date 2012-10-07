<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Template;

use Asar\Routing\Route;
use Asar\Template\Engine\EngineRegistry;
use Asar\Http\Message\Request;

/**
 * Assembles templates and template engine for rendering
 */
class Assembler
{
    private $finder;

    private $registry;

    private $request;

    /**
     * Constructor
     *
     * @param TemplateFinder $finder   finds templates
     * @param EngineRegistry $registry template engine registry
     * @param Request        $request  the current requestrequest
     */
    public function __construct(TemplateFinder $finder, EngineRegistry $registry, Request $request)
    {
        $this->finder = $finder;
        $this->registry = $registry;
        $this->request = $request;
    }

    /**
     * Gets an assembled template engine for a specified route
     *
     * @param Route $route the current matched route for the
     */
    public function getTemplate(Route $route)
    {
        $this->finder->find($route->getName(), array('method' => $this->request->getMethod(), 'type' => 'html'));
    }

}