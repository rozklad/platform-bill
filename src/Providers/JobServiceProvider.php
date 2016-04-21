<?php namespace Sanatorium\Bill\Providers;

use Cartalyst\Support\ServiceProvider;

class JobServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Bill\Models\Job']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.bill.job.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.bill.job', 'Sanatorium\Bill\Repositories\Job\JobRepository');

		// Register the data handler
		$this->bindIf('sanatorium.bill.job.handler.data', 'Sanatorium\Bill\Handlers\Job\JobDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.bill.job.handler.event', 'Sanatorium\Bill\Handlers\Job\JobEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.bill.job.validator', 'Sanatorium\Bill\Validator\Job\JobValidator');
	}

}
