<?php /** MicroDispatcher */

namespace Micro\base;

/**
 * Dispatcher class file.
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @version 1.0
 * @since 1.0
 */
class Dispatcher
{
    /** @var array $listeners listeners objects on events */
    protected $listeners = [];

    /**
     * Add listener on event
     *
     * @access public
     *
     * @param string $listener listener name
     * @param array $event ['Object', 'method']
     * @param int|null $prior priority
     *
     * @return void
     */
    public function addListener($listener, array $event = [], $prior = null)
    {
        if (!$prior) {
            $this->listeners[$listener][] = $event;
        } else {
            array_splice($this->listeners, $prior, 0, $event);
        }
    }

    /**
     * Send signal to run event
     *
     * @access public
     *
     * @param string $listener listener name
     *
     * @return void
     */
    public function signal($listener)
    {
        if ($this->listeners) {
            foreach ($this->listeners AS $key => $obj) {
                if ($key === $listener) {
                    call_user_func($obj);
                }
            }
        }
    }
}