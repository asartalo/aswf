<?php
/**
 * This file is part of the Asar Web Framework
 *
 * (c) Wayne Duran <asartalo@projectweb.ph>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asar\Tests\Unit\Session;

use Asar\TestHelper\TestCase;
use Asar\Session\ArrayStore;

/**
 * Specification for Asar\Session\ArrayStore
 */
class ArrayStoreTest extends TestCase
{

    /**
     * Setup
     */
    public function setUp()
    {
        $this->store = new ArrayStore(array(
            'foo' => "Foo"
        ));
    }

    /**
     * Session values can be obtained through keys
     */
    public function testGet()
    {
        $this->assertEquals('Foo', $this->store->get('foo'));
    }

    /**
     * Session values can be set
     */
    public function testSet()
    {
        $this->store->set('bar', 'Bar');
        $this->assertEquals('Bar', $this->store->get('bar'));
    }

    /**
     * Checking an unset value
     */
    public function testHasReturnsFalseIfKeyIsNotSet()
    {
        $this->assertFalse($this->store->has('baz'));
    }

    /**
     * Checking a set value
     */
    public function testHasReturnsTrueIfKeyIsSet()
    {
        $this->assertTrue($this->store->has('foo'));
    }

    /**
     * Removing a session value
     */
    public function testRemoveDeletesASessionValue()
    {
        $this->store->remove('foo');
        $this->assertFalse($this->store->has('foo'));
    }

    /**
     * Remove alias
     */
    public function testDeleteAsRemoveAlias()
    {
        $this->store->delete('foo');
        $this->assertFalse($this->store->has('foo'));
    }

    /**
     * Can be destroyed
     */
    public function testDestroy()
    {
        $this->store->destroy();
        $this->assertFalse($this->store->has('foo'));
    }

}