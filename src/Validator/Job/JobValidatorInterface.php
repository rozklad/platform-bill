<?php namespace Sanatorium\Bill\Validator\Job;

interface JobValidatorInterface {

	/**
	 * Updating a job scenario.
	 *
	 * @return void
	 */
	public function onUpdate();

}
