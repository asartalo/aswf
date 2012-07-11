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
 * A route to a resource
 */
class Route
{
    private $name;

    private $values = array();

    /**
     * Construct
     *
     * @param string $name   the resource name
     * @param array  $values the values of the paths
     */
    public function __construct($name, array $values)
    {
        $this->name = $name;
        $this->values = $values;
    }

    /**
     * Get the route name
     *
     * @return string the route's resource name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the values
     *
     * @return array values
     */
    public function getValues()
    {
        return $this->values;
    }

}