<?php

namespace Dhii\Collection\FuncTest;

use Dhii\Collection;

/**
 * Tests {@see \Dhii\Collection\AbstractCallbackCollection}.
 *
 * @since [*next-version*]
 */
class AbstractCallbackCollectionTest extends \Xpmock\TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return \Dhii\Collection\AbstractCallbackCollection The new instance of the test subject.
     */
    public function createInstance()
    {
        $me = $this;
        $instance = $this->mock('Dhii\\Collection\\AbstractCallbackCollection')
                ->_validateItem()
                ->_createCallbackIterator(function($callback, $items) use ($me) {
                    return $me->createCallbackIterator($items, $callback);
                })
                ->new();

        $reflection = $this->reflect($instance);
        $reflection->_construct();

        return $instance;
    }

    /**
     * Creates a new callback iterator.
     *
     * @since [*next-version*]
     *
     * @return Collection\AbstractCallbackIterator The new callback iterator instance.
     */
    public function createCallbackIterator($items, $callback)
    {
        $mock = $this->mock('Dhii\\Collection\\AbstractCallbackIterator')
                ->new();

        $reflection = $this->reflect($mock);

        $reflection->_setItems($items);
        $reflection->_setCallback($callback);

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subejct can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf('Dhii\\Collection\\AbstractCallbackCollection', $subject, 'Subject is not of the required type');
    }

    /**
     * Tests whether the callback iterator retrieved is what is created internally, and it has correct data set.
     *
     * @since [*next-version*]
     */
    public function testEach()
    {
        $subject = $this->createInstance();
        $reflection = $this->reflect($subject);

        $callback = function($key, $item, &$isContinue) {
            return strtoupper($item);
        };
        $data = array('apple', 'banana');
        $iterator = $reflection->_each($callback, $data);

        $iteratorReflection = $this->reflect($iterator);
        $this->assertEquals($data, $iteratorReflection->_getItems(), 'Iterator does not have the items set');
        $this->assertEquals($callback, $iteratorReflection->_getCallback(), 'Iterator does not have the callback set');
    }
}
