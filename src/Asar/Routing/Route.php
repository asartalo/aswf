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
    private $path;

    private $name;

    private $values = array();

    private $serviceId = 'request.resource.default';

    /**
     * Construct
     *
     * @param string  $path    the input path
     * @param string  $name    the resource name
     * @param array   $values  the values of the paths
     * @param service $service (optional) the service definition name
     */
    public function __construct($path, $name, array $values, $service = '')
    {
        $this->path = $path;
        $this->name = $name;
        $this->values = $values;
        if ($service) {
            $this->serviceId = $service;
        }
    }

    /**
     * Get the original path
     *
     * @return string the route's original input path
     */
    public function getPath()
    {
        return $this->path;
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

    /**
     * See if this is null. Default is false.
     *
     * @return boolean whether this route is null or not
     */
    public function isNull()
    {
        return false;
    }

    /**
     * Get the service name
     *
     * @return string the service name
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

}