<?php namespace Sanatorium\Bill\Handlers\Bill;

use Sanatorium\Bill\Models\Bill;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface BillEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a bill is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a bill is created.
	 *
	 * @param  \Sanatorium\Bill\Models\Bill  $bill
	 * @return mixed
	 */
	public function created(Bill $bill);

	/**
	 * When a bill is being updated.
	 *
	 * @param  \Sanatorium\Bill\Models\Bill  $bill
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Bill $bill, array $data);

	/**
	 * When a bill is updated.
	 *
	 * @param  \Sanatorium\Bill\Models\Bill  $bill
	 * @return mixed
	 */
	public function updated(Bill $bill);

	/**
	 * When a bill is deleted.
	 *
	 * @param  \Sanatorium\Bill\Models\Bill  $bill
	 * @return mixed
	 */
	public function deleted(Bill $bill);

}
