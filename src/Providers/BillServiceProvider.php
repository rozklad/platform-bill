<?php namespace Sanatorium\Bill\Providers;

use Cartalyst\Support\ServiceProvider;

class BillServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Bill\Models\Bill']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.bill.bill.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.bill.bill', 'Sanatorium\Bill\Repositories\Bill\BillRepository');

		// Register the data handler
		$this->bindIf('sanatorium.bill.bill.handler.data', 'Sanatorium\Bill\Handlers\Bill\BillDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.bill.bill.handler.event', 'Sanatorium\Bill\Handlers\Bill\BillEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.bill.bill.validator', 'Sanatorium\Bill\Validator\Bill\BillValidator');
	}

}
