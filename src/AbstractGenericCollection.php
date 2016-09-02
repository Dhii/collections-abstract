<?php

namespace Dhii\Collection;

/**
 * A default implementation of a general purpose collection.
 *
 * @since [*next-version*]
 */
abstract class AbstractGenericCollection extends AbstractSearchableCollection
{
    /**
     * @since [*next-version*] 
     *
     * @param mixed[]|\Traversable $items The items to populate this collection with.
     */
    public function __construct($items = null)
    {
        if (!is_null($items)) {
            $this->_addItems($items);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*] 
     */
    public function _validateItem($item)
    {
        return true;
    }
}
