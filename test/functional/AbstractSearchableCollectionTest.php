<?php

namespace Dhii\Collection\FuncTest;

use Dhii\Collection;

/**
 * Tests {@see \Dhii\Collection\AbstractSearchableCollection}.
 *
 * @since [*next-version*]
 */
class AbstractSearchableCollectionTest extends \Xpmock\TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return \Dhii\Collection\AbstractSearchableCollection The new instance of the test subject.
     */
    public function createInstance()
    {
        $me = $this;
        $instance = $this->mock('Dhii\\Collection\\AbstractSearchableCollection')
                ->_validateItem(function($item) {
                    if (!is_string($item)) {
                        throw new \RuntimeException('Item must be a string!');
                    }
                })
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
        $me = $this;
        $mock = $this->mock('Dhii\\Collection\\Stub\\CallbackIterator')
                ->new();
        /* @var $mock Dhii\Collection\Stub\CallbackIterator */

        $mock->setItems($items);
        $mock->setCallback($callback);

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
    public function testSearch()
    {
        $subject = $this->createInstance();
        $reflection = $this->reflect($subject);
        $subjectClass = get_class($subject);

        $callback = function($key, $item, &$isContinue) {
            return stripos($item, 'b') !== false
                    ? $item
                    : null;
        };
        $data = array('apple', 'banana', 'strawberry');
        $result = $reflection->_search($callback, $data);
        $this->assertInstanceOf($subjectClass, $result, 'Search result is not of the correct type');

        $this->assertEquals(array('banana', 'strawberry'), $this->reflect($result)->_getItems(), 'Search result set is incorrect');
    }
}
