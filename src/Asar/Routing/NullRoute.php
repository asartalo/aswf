<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Routing;

/**
 * A null route that signifies that a route cannot be reached
 */
class NullRoute extends Route
{

    /**
     * Constructor
     *
     * @param string $path the input path
     */
    public function __construct($path)
    {
        parent::__construct($path, '', array(), 'asar.resource.generic.notfound');
    }

    /**
     * Signifies that this is null
     *
     * @return boolean always true because this is a null route
     */
    public function isNull()
    {
        return true;
    }

}