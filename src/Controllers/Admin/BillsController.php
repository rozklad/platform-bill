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

		$currentYearLast = $this->bills->where('year', date('Y'))->orderBy('num', 'DESC')->first();

        if ( is_object($currentYearLast) ) {
            $suggested_num = $currentYearLast->num + 1;
        } else {
            $suggested_num = date('Y') . '001';
        }

        $default_due_days = 14; // @todo: make configurable

        $suggested_issue_date = \Carbon\Carbon::now()->format('Y-m-d');
        $suggested_due_date = \Carbon\Carbon::now()->addDays($default_due_days)->format('Y-m-d');

        $suggested_year = date('Y');

        // @todo: make configurable
        $possible_means_of_payment = [
            'Bank transfer'
        ];

        $buyers = \Sanatorium\Clients\Models\Client::where('supplier', 0)->get();
        $suppliers = \Sanatorium\Clients\Models\Client::where('supplier', 1)->get();

        $supported_currencies = [
            'Kč' => 'Kč',
            'EUR' => 'EUR',
            'CHF' => 'CHF',
            'USD' => 'USD',
        ];

        // @todo: fetch from buyer
        $suggested_iban = 'CZ91 0800 0000 0019 3699 8183';
        $suggested_swift = 'GIBACZPX';
        $suggested_account_number = '1936998183/0800';

		// Show the page
		return view('sanatorium/bill::bills.form', compact(
		    'mode',
            'bill',
            'suggested_num',
            'suggested_issue_date',
            'suggested_due_date',
            'suggested_year',
            'possible_means_of_payment',
            'buyers',
            'suppliers',
            'supported_currencies',
            'suggested_iban',
            'suggested_swift',
            'suggested_account_number'
        ));
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
		list($messages, $bill) = $this->bills->store($id, request()->except(['jobs']));

        // Sync jobs
        if ( request()->has('jobs') )
        {

            $jobs = request()->get('jobs');

            if ( is_array($jobs) )
            {

                $jobs_to_sync = [];

                foreach( $jobs as $job )
                {

                    if ( isset($job['id']) )
                    {
                        $job = \Sanatorium\Bill\Models\Job::find($job['id']);

                        $job->update([
                            'quantity'    => $job['quantity'],
                            'description' => $job['description'],
                            'price'       => $job['price'],
                            'currency'    => $job['currency'],
                            'bill_id'     => $bill->id
                        ]);

                        $jobs_to_sync[] = $job;
                    } else
                    {
                        $jobs_to_sync[] = \Sanatorium\Bill\Models\Job::create([
                            'quantity'    => $job['quantity'],
                            'description' => $job['description'],
                            'price'       => $job['price'],
                            'currency'    => $job['currency'],
                            'bill_id'     => $bill->id
                        ]);
                    }

                }

                $current_jobs = [];
                foreach( $jobs_to_sync as $job_to_sync )
                {
                    $current_jobs[] = $job_to_sync->id;
                }

                $old_jobs = $bill->jobs->lists('id')->toArray();

                $jobs_to_delete = array_diff($old_jobs, $current_jobs);

                $bill->jobs()->whereIn('id', $jobs_to_delete)->delete();
                $bill->jobs()->saveMany($jobs_to_sync);

            }

        }

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
