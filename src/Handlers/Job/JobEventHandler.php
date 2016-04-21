<?php namespace Sanatorium\Bill\Handlers\Job;

use Illuminate\Events\Dispatcher;
use Sanatorium\Bill\Models\Job;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class JobEventHandler extends BaseEventHandler implements JobEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.bill.job.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.bill.job.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.bill.job.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.bill.job.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.bill.job.deleted', __CLASS__.'@deleted');
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
	public function created(Job $job)
	{
		$this->flushCache($job);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Job $job, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Job $job)
	{
		$this->flushCache($job);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Job $job)
	{
		$this->flushCache($job);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Bill\Models\Job  $job
	 * @return void
	 */
	protected function flushCache(Job $job)
	{
		$this->app['cache']->forget('sanatorium.bill.job.all');

		$this->app['cache']->forget('sanatorium.bill.job.'.$job->id);
	}

}
