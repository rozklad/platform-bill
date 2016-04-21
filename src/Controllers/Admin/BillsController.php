<?php namespace Sanatorium\Bill\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Bill\Repositories\Bill\BillRepositoryInterface;

class BillsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Bill repository.
	 *
	 * @var \Sanatorium\Bill\Repositories\Bill\BillRepositoryInterface
	 */
	protected $bills;

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
	 * @param  \Sanatorium\Bill\Repositories\Bill\BillRepositoryInterface  $bills
	 * @return void
	 */
	public function __construct(BillRepositoryInterface $bills)
	{
		parent::__construct();

		$this->bills = $bills;
	}

	/**
	 * Display a listing of bill.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/bill::bills.index');
	}

	/**
	 * Datasource for the bill Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->bills->grid();

		$columns = [
			'id',
			'num',
			'issue_date',
			'due_date',
			'means_of_payment',
			'payment_symbol',
			'account_number',
			'iban',
			'swift',
			'buyer_id',
			'supplier_id',
			'year',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.bill.bills.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new bill.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new bill.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating bill.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating bill.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified bill.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->bills->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/bill::bills/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.bill.bills.all');
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
				$this->bills->{$action}($row);
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
		// Do we have a bill identifier?
		if (isset($id))
		{
			if ( ! $bill = $this->bills->find($id))
			{
				$this->alerts->error(trans('sanatorium/bill::bills/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.bill.bills.all');
			}
		}
		else
		{
			$bill = $this->bills->createModel();
		}

		// Show the page
		return view('sanatorium/bill::bills.form', compact('mode', 'bill'));
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
		// Store the bill
		list($messages) = $this->bills->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/bill::bills/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.bill.bills.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
