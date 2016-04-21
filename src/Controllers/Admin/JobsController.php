<?php namespace Sanatorium\Bill\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Bill\Repositories\Job\JobRepositoryInterface;

class JobsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Bill repository.
	 *
	 * @var \Sanatorium\Bill\Repositories\Job\JobRepositoryInterface
	 */
	protected $jobs;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Bill\Repositories\Job\JobRepositoryInterface  $jobs
	 * @return void
	 */
	public function __construct(JobRepositoryInterface $jobs)
	{
		parent::__construct();

		$this->jobs = $jobs;
	}

	/**
	 * Display a listing of job.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/bill::jobs.index');
	}

	/**
	 * Datasource for the job Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->jobs->grid();

		$columns = [
			'id',
			'bill_id',
			'quantity',
			'description',
			'price',
			'currency',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.bill.jobs.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new job.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new job.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating job.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating job.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified job.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->jobs->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/bill::jobs/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.bill.jobs.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->jobs->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a job identifier?
		if (isset($id))
		{
			if ( ! $job = $this->jobs->find($id))
			{
				$this->alerts->error(trans('sanatorium/bill::jobs/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.bill.jobs.all');
			}
		}
		else
		{
			$job = $this->jobs->createModel();
		}

		// Show the page
		return view('sanatorium/bill::jobs.form', compact('mode', 'job'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the job
		list($messages) = $this->jobs->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/bill::jobs/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.bill.jobs.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
