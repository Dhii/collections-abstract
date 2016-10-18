<?php

namespace Dhii\Collection\Stub;

use Dhii\Collection;

/**
 * A stub of what could be a callback iterator implementation.
 *
 * This is necessary because the callback iterator must implement {@see \Iterator}
 * to work correctly in a loop, which is what we are also testing here:
 * the iteration goes on elsewhere, and in this case we cannot replace it to
 * invokation of protected methods such as `_next()`, `_current()` and `_valid()`.
 *
 * @since [*next-version*]
 */
class CallbackIterator extends Collection\AbstractCallbackIterator implements Collection\TraversableCollectionInterface
{
    /**
     * @inheritdoc
     *
     * @since [*next-version*]
     */
    public function contains($item)
    {
        return $this->_hasItem($item);
    }

    /**
     * @inheritdoc
     *
     * @since [*next-version*]
     */
    public function current()
    {
        return $this->_currentProcessed();
    }

    /**
     * @inheritdoc
     *
     * @since [*next-version*]
     */
    public function key()
    {
        return $this->_key();
    }

    /**
     * @inheritdoc
     *
     * @since [*next-version*]
     */
    public function next()
    {
        $this->_next();
    }

    /**
     * @inheritdoc
     *
     * @since [*next-version*]
     */
    public function rewind()
    {
        return $this->_rewindResetCallback();
    }

    /**
     * @inheritdoc
     *
     * @since [*next-version*]
     */
    public function valid()
    {
        return $this->_validCheckCallback();
    }

    /**
     * @since [*next-version*]
     *
     * @param array|\Traversable $items
     */
    public function setItems($items)
    {
        $this->_setItems($items);
    }

    /**
     * @since [*next-version*]
     *
     * @param callable $callback
     */
    public function setCallback($callback)
    {
        $this->_setCallback($callback);
    }
}
