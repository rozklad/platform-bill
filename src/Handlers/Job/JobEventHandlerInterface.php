<?php namespace Sanatorium\Bill\Handlers\Job;

use Sanatorium\Bill\Models\Job;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface JobEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a job is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a job is created.
	 *
	 * @param  \Sanatorium\Bill\Models\Job  $job
	 * @return mixed
	 */
	public function created(Job $job);

	/**
	 * When a job is being updated.
	 *
	 * @param  \Sanatorium\Bill\Models\Job  $job
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Job $job, array $data);

	/**
	 * When a job is updated.
	 *
	 * @param  \Sanatorium\Bill\Models\Job  $job
	 * @return mixed
	 */
	public function updated(Job $job);

	/**
	 * When a job is deleted.
	 *
	 * @param  \Sanatorium\Bill\Models\Job  $job
	 * @return mixed
	 */
	public function deleted(Job $job);

}
