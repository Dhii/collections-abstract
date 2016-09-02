<?php

namespace Dhii\Collection;

use UnexpectedValueException;

/**
 * Common functionality for callback iterators.
 *
 * @since [*next-version*]
 */
abstract class AbstractCallbackIterator extends AbstractIterableCollection implements CallbackIteratorInterface
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
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function current()
    {
        $item = parent::current();

        $isContinue = true;
        $item       = $this->_applyCallback($this->key(), $item, $isContinue);

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
        $callback = $this->getCallback();
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
     * @throws \Exception
     */
    protected function _validateCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new UnexpectedValueException(sprintf('Could not apply callback: Callback must be callable'));
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
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function rewind()
    {
        $this->_halt(false);
        parent::rewind();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function valid()
    {
        return $this->_isHalted()
            ? false
            : parent::valid();
    }
}
