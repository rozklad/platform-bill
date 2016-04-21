<?php namespace Sanatorium\Bill\Handlers\Bill;

use Illuminate\Events\Dispatcher;
use Sanatorium\Bill\Models\Bill;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class BillEventHandler extends BaseEventHandler implements BillEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.bill.bill.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.bill.bill.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.bill.bill.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.bill.bill.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.bill.bill.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Bill $bill)
	{
		$this->flushCache($bill);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Bill $bill, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Bill $bill)
	{
		$this->flushCache($bill);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Bill $bill)
	{
		$this->flushCache($bill);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Bill\Models\Bill  $bill
	 * @return void
	 */
	protected function flushCache(Bill $bill)
	{
		$this->app['cache']->forget('sanatorium.bill.bill.all');

		$this->app['cache']->forget('sanatorium.bill.bill.'.$bill->id);
	}

}
