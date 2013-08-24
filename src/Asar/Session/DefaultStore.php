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
 *
 * Use this instead of directly accessing the global session object
 */
class DefaultStore implements StoreInterface
{

    /**
     * Starts session
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * Destroys session
     */
    public function destroy()
    {
        session_destroy();
    }

    /**
     * Check if a value is stored using a key
     *
     * @param string $key
     *
     * @return boolean
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Obtains a value stored through a key
     *
     * @param string $key
     *
     * @return mixed the stored value
     */
    public function get($key)
    {
        return $_SESSION[$key];
    }

    /**
     * Sets a session value
     *
     * @param string $key   the key
     * @param mixed  $value the value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Removes a set session value
     *
     * @param string $key of the value to remove
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Alias of remove()
     *
     * @param string $key
     *
     * @see remove()
     */
    public function delete($key)
    {
        $this->remove($key);
    }

}