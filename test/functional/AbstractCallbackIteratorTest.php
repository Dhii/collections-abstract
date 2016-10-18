<?php

namespace Dhii\Collection\FuncTest;

/**
 * Testing {@see \Dhii\Collection\AbstractCallbackIterator}.
 *
 * @since [*next-version*]
 */
class AbstractCallbackIteratorTest extends \Xpmock\TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return \Dhii\Collection\AbstractCallbackIterator The new instance of the test subject.
     */
    public function createInstance()
    {
        $mock = $this->mock('Dhii\\Collection\\AbstractCallbackIterator')
                ->_validateItem()
                ->new();

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();
        $reflection = $this->reflect($subject);

        $this->assertInstanceOf('Dhii\\Collection\\AbstractCallbackIterator', $subject, 'The subject is not of a required type');
    }

    /**
     * Tests whether iteration happens correctly and callback has desired effect.
     *
     * @since [*next-version*]
     */
    public function testIteration()
    {
        $subject = $this->createInstance();
        $reflection = $this->reflect($subject);

        $index = 0;
        $items = array();
        $data = array('one' => 'banana', 'two' => 'orange', 'three' => 'pineapple');
        $reflection->items = $data;
        $reflection->_setCallback(function($key, $item, &$isContinue) use (&$index) {
            // Stop after second element
            if ($index === 1) {
                $isContinue = false;
            }

            $index++;

            return strtoupper($item);
        });

        while ($reflection->_validCheckCallback()) {
            $items[$reflection->_key()] = $reflection->_currentProcessed();

            $reflection->_next();
        }
        $reflection->_rewindResetCallback();
        $this->assertEquals(array('one' => 'BANANA', 'two' => 'ORANGE'), $items, 'Iteration did not yield correct results');
    }

    /**
     * Tests that an invalid callback cannot be set.
     *
     * @since [*next-version*]
     *
     * @expectedException UnexpectedValueException
     * @expectedExceptionMessage format
     */
    public function testCallbackInvalid()
    {
        $subject = $this->createInstance();
        $reflection = $this->reflect($subject);

        $reflection->_setCallback(new \stdClass());
    }
    /**
     * Tests that an exception is thrown when attempting to ivoke a callback
     * that is not callable.
     *
     * @since [*next-version*]
     *
     * @expectedException UnexpectedValueException
     * @expectedExceptionMessage callable
     */
    public function testCallbackNotCallable()
    {
        $subject = $this->createInstance();
        $reflection = $this->reflect($subject);

        $reflection->callback = (array('NonExistingClass', 'nonExistingMethod'));
        $reflection->items = array('apple');
        $reflection->_currentProcessed();
    }
}
