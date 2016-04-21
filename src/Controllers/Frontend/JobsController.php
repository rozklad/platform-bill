<?php namespace Sanatorium\Bill\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class JobsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/job::index');
	}

}
