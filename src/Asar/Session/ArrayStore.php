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
 * Use for testing only...
 */
class ArrayStore implements StoreInterface
{

    private $storage;

    /**
     * @param array $storage a place where the session will be persisted
     */
    public function __construct(array $storage)
    {
        $this->storage = $storage;
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
        return isset($this->storage[$key]);
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
        return $this->storage[$key];
    }

    /**
     * Sets a session value
     *
     * @param string $key   the key
     * @param mixed  $value the value
     */
    public function set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     * Removes a set session value
     *
     * @param string $key of the value to remove
     */
    public function remove($key)
    {
        unset($this->storage[$key]);
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

    /**
     * Destroys a session
     */
    public function destroy()
    {
        array_splice($this->storage, 0);
    }

}