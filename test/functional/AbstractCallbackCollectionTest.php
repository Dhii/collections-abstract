<?php

namespace Dhii\Collection\FuncTest;

use Dhii\Collection;

/**
 * Tests {@see \Dhii\Collection\AbstractCallbackCollection}.
 *
 * @since 0.1.0
 */
class AbstractCallbackCollectionTest extends \Xpmock\TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1.0
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
     * @param mixed[]|\Traversable $items The items for the callback iterator.
     * @param callable $callback The callback for the callback iterator.
     *
     * @return Collection\AbstractCallbackIterator The new callback iterator instance.
     */
    public function createCallbackIterator($items, $callback)
    {
        $mock = $this->mock('Dhii\\Collection\\AbstractCallbackIterator')
                ->_validateItem()
                ->new();

        $reflection = $this->reflect($mock);

        $reflection->_setItems($items);
        $reflection->_setCallback($callback);

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subejct can be created.
     *
     * @since 0.1.0
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf('Dhii\\Collection\\AbstractCallbackCollection', $subject, 'Subject is not of the required type');
    }

    /**
     * Tests whether the each() method runs as required.
     *
     * @since 0.1.0
     */
    public function testEach()
    {
        $me = $this;
        $subject = $this->createInstance();
        $reflection = $this->reflect($subject);

        $reflection->_addItem('apple');
        $reflection->_addItem('banana');
        $reflection->_addItem('orange');
        $items = $reflection->_getItems();

        $iterations = 0;
        $iterator = $reflection->_each(
            /* In the `use` statement, the `$iterations` variable is byref to bypass early binding:
             * http://stackoverflow.com/q/8403908#comment37564320_8403958
             */
            function($key, $item, &$isContinue) use ($me, &$iterations, $items) {
                $iterations++;
                if ($iterations === 2) {
                    // This tests whether breaking out of the loop works from the callback
                    $isContinue = false;
                }

                $me->assertContains($item, $items, 'The current item is not in the collection');
            }
        );

        foreach ($iterator as $_item) {
            // Callback only runs on each iteration.
        }

        $this->assertEquals(2, $iterations, 'The callback has not been invoked the required number of times');
    }
}
