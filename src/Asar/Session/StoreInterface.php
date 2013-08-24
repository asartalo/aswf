<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Session;

/**
 * A web application session abstraction
 */
interface StoreInterface
{
    /**
     * Check if a value is stored using a key
     *
     * @param string $key
     *
     * @return boolean
     */
    public function has($key);

    /**
     * Obtains a value stored through a key
     *
     * @param string $key
     *
     * @return mixed the stored value
     */
    public function get($key);

    /**
     * Sets a session value
     *
     * @param string $key   the key
     * @param mixed  $value the value
     */
    public function set($key, $value);

    /**
     * Removes a set session value
     *
     * @param string $key of the value to remove
     */
    public function remove($key);

    /**
     * Alias of remove()
     *
     * @param string $key
     *
     * @see remove()
     */
    public function delete($key);

}