<?php

namespace Dhii\Collection;

/**
 * Common functionality for callback iterators.
 *
 * Intended for implementation of {@see \Dhii\Collection\CallbackIteratorInterface}.
 *
 * @since [*next-version*]
 */
abstract class AbstractCallbackIterator extends AbstractWritableCollection
{
    protected $callback;
    protected $isHalted = false;

    /**
     * Sets the callback that will be applied to each element of this collection.
     *
     * @since [*next-version*]
     *
     * @param callable $callback The callback for this iterator to apply.
     *
     * @return AbstractCallbackIterator This instance.
     */
    protected function _setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @see CallbackIteratorInterface::getCallback()
     * @since [*next-version*]
     */
    protected function _getCallback()
    {
        return $this->callback;
    }

    /**
     * Retrieves the value of the current element after the callback is applied to it.
     *
     * @see \Iterator::current()
     * @since [*next-version*]
     *
     * @return mixed The processed current element.
     */
    protected function _currentProcessed()
    {
        $item = $this->_current();

        $isContinue = true;
        $item       = $this->_applyCallback($this->_key(), $item, $isContinue);

        if (!$isContinue) {
            $this->_halt();
        }

        return $item;
    }

    /**
     * Applies the callback to an item, and returns the result.
     *
     * See {@see CallbackIterableInterface::each()} for details about the callback.
     *
     * @since [*next-version*]
     *
     * @param string|int $key  The key of the current item.
     * @param mixed      $item The item to apply the callback to.
     *
     * @return mixed The return value of the callback.
     */
    public function _applyCallback($key, $item, &$isContinue)
    {
        $callback = $this->_getCallback();
        $this->_validateCallback($callback);

        return call_user_func_array($callback, array($key, $item, &$isContinue));
    }

    /**
     * Determines if a value is a valid callback.
     *
     * @since [*next-version*]
     *
     * @param mixed $callback The value to check.
     *
     * @throws \UnexpectedValueException If the callback cannot be invoked.
     */
    protected function _validateCallback($callback)
    {
        if (!is_callable($callback)) {
            throw $this->_createUnexpectedValueException(sprintf('Could not apply callback: Callback must be callable'));
        }
    }

    /**
     * Determines if a value is a valid callback.
     *
     * @since [*next-version*]
     *
     * @param mixed $callback The value to check.
     *
     * @return bool True if the callback is valid; false otherwise.
     */
    protected function _isValidCallback($callback)
    {
        try {
            $this->_validateCallback($callback);
        } catch (Exception $ex) {
            return false;
        }

        return true;
    }

    /**
     * Stop iteration of the current loop.
     *
     * Because the callback executes outside of the scope of the loop, it is not
     * possible to halt iteration immediately. However, calling this with `true`
     * will cause the next call to `valid()` to return `false`, which signals
     * that the loop should break.
     *
     * @since [*next-version*]
     *
     * @param bool $isHalted Whether or not iteration should stop.
     *
     * @return bool True if iteration was halted before the new value took effect;
     *              false otherwise.
     */
    protected function _halt($isHalted = true)
    {
        $wasHalted      = (bool) $this->isHalted;
        $this->isHalted = (bool) $isHalted;

        return $wasHalted;
    }

    /**
     * Check whether or not iteration is stopped.
     *
     * @see _halt()
     * @since [*next-version*]
     *
     * @return bool True of iteration is currently halted; false otherwise.
     */
    protected function _isHalted()
    {
        return $this->isHalted;
    }

    /**
     * Rewinds the internal pointer, also resetting callback data.
     *
     * @since [*next-version*]
     */
    protected function _rewindResetCallback()
    {
        $this->_halt(false);
        parent::_rewind();
    }

    /**
     * Determines the validity of the current element, taking callback into account.
     *
     * @since [*next-version*]
     *
     * @return bool True if the current element is valid and the callback is not halted; false otherwise.
     */
    protected function _validCheckCallback()
    {
        return $this->_isHalted()
            ? false
            : parent::_valid();
    }
}
