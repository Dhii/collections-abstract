<?php

namespace Dhii\Collection;

/**
 * Common functionality for callback collections.
 *
 * @since [*next-version*]
 */
abstract class AbstractCallbackCollectionBase extends AbstractAggregatableCollection
{
    /**
     * Retrieve a callback instance that will apply a callback to each item in the list.
     *
     * See {@see CallbackIterableInterface::each()} for details about the callback.
     *
     * @since [*next-version*]
     *
     * @param callable           $callback The callback to apply to each item in the list. Default: itself.
     * @param array|\Traversable $items    The list of items to iterate over.
     *
     * @return CallbackIteratorInterface The callback iterator that will iterate over the list.
     */
    protected function _each($callback, $items = null)
    {
        if (is_null($items)) {
            $items = $this;
        }

        return $this->_createCallbackIterator($callback, $items);
    }

    /**
     * Create a new instance of callback iterator for the specified callback and item list.
     *
     * @since [*next-version*]
     *
     * @param callable           $callback The callback for the iterator to apply to each item.
     * @param array|\Traversable $items    The list of items for the iterator to iterate over.
     *
     * @return CallbackIterator The new callback iterator.
     */
    protected function _createCallbackIterator($callback, $items)
    {
        return new CallbackIterator($items, $callback);
    }
}
