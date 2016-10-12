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
}
