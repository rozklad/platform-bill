<?php namespace Sanatorium\Bill\Handlers\Job;

class JobDataHandler implements JobDataHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function prepare(array $data)
	{
		return $data;
	}

}
