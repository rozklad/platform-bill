<?php namespace Sanatorium\Bill\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Sanatorium\Bill\Repositories\Bill\BillRepositoryInterface;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Sanatorium\Bill\Repositories\Job\JobRepositoryInterface;
use PDF;
use File;
use Event;
use Response;


class BillsController extends Controller {

    /**
     * Constructor.
     *
     * @param  \Sanatorium\Bill\Repositories\Bill\BillRepositoryInterface  $bills
     * @return void
     */
    public function __construct(BillRepositoryInterface $bills, JobRepositoryInterface $jobs)
    {
        parent::__construct();

        $this->bills = $bills;

        $this->jobs = $jobs;
    }

    /**
     * Return the main view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        if ( Sentinel::check() ) {

            $bills  = app('sanatorium.bill.bill')->orderBy('num', 'desc')->get();

            /*$user = Sentinel::getUser();

            $bills = $bills->where('supplier_id', $user->id)->get();*/

            return view('sanatorium/bill::index', compact('bills'));

        }

        return redirect('/login');


    }

    public function create($id = null)
    {

        /*

        // Store the bill
        list($messages) = $this->bills->store($id, request()->all());

        // Do we have any errors?
        if ($messages->isEmpty())
        {

            //return redirect()->route('admin.sanatorium.bill.bills.all');
        }

        $this->alerts->error($messages, 'form');

        /*$bills = app('sanatorium.bill.bill');

        $bill = $bills->where('num', request()->num);*/

        //return redirect()->back()->withInput();

        return $this->processForm('create', $id);
    }

    public function newBill($id = null)
    {

        $bills = app('sanatorium.bill.bill');

        $clients = app('sanatorium.clients.client')->get();

        $suppliers = $clients->where('supplier', 1);

        $buyers = $clients->where('supplier', 0);

        $year = date("Y");

        if ( $id ) {

            $bill = $bills->find($id);

            $num = $bill->num;

            $issue_date = date('Y-m-d', strtotime($bill->issue_date));

            $due_date = date('Y-m-d', strtotime($bill->due_date));

        } else {

            $next_bill = count($bills->where('year', $year)->get()) + 1;

            $three_digit = str_pad($next_bill, 3, "0", STR_PAD_LEFT);

            $num = $year . $three_digit;

            $issue_date = date("Y-m-j");

            $due_date = date('Y-m-d', strtotime('+14 days'));

            /*$bill = [
            "num" => $num,
            "issue_date" => date("Y-m-j"),
            "due_date" => date('Y-m-d', strtotime('+14 days')),
            "means_of_payment" => "",
            "payment_symbol" => "",
            "account_number" => "",
            "iban" => "",
            "swift" => "",
            "buyer_id" => "1",
            "supplier_id" => "1",
            "year" => $year,
            ];

            list($messages) = $this->bills->store(null, $bill);

            $actual_bill = $this->bills->get()->last();

            /*$users = app('platform.users')->get();

            $clients = app('sanatorium.clients.client')->get();

            $year = date("Y");

            $num = count($bills->where('year', $year)->get()) + 1;

            return view('sanatorium/bill::new', compact('num', 'users', 'clients'));

            return redirect()->route('sanatorium.bill.bills.edit', $actual_bill->id);*/

        }

        return view('sanatorium/bill::new', compact('num', 'issue_date', 'due_date', 'suppliers', 'buyers'));

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
        list($messages_bill, $bill) = $this->bills->store($id, request()->bill[0]);

        // Store the job

        foreach ( request()->jobs as $job ) {

            $job["bill_id"] = $bill->id;

            list($messages_job) = $this->jobs->store(null, $job);

        }

        // Do we have any errors?
        if ( $messages_bill->isEmpty() || $messages_job->isEmpty() )
        {
            $this->alerts->success(trans("sanatorium/bill::bills/message.success.{$mode}"));

            $jobs = app('sanatorium.bill.job')->where('bill_id', $bill->id)->get();

            $buyer = app('sanatorium.clients.client')->where('id', $bill->buyer_id)->first();

            $supplier = app('sanatorium.clients.client')->where('id', $bill->supplier_id)->first();

            $pdf = PDF::loadView('sanatorium/bill::pdf/template', compact('bill', 'jobs', 'buyer', 'supplier'));

            //$pdf = PDF::loadHtml('<h1>Ahoj</h1>');

            $path = storage_path() . "/bills" . "/" . $bill->year;

            if ( ! file_exists($path) ) {

                File::makeDirectory($path, 0775, true);

            }

            $file_path = $path . '/' . $bill->num . '.pdf';

            $pdf->save($file_path);

            return redirect()->route('sanatorium.bill.bills.index');
        }

        $this->alerts->error($messages, 'form');

        $bill = app('sanatorium.bill.bill')->where('id', $id)->first();

        $jobs = app('sanatorium.bill.job')->where('bill_id', $id)->get();

        $buyer = app('sanatorium.bill.client')->where('id', $bill->buyer_id)->first();

        return redirect()->back()->withInput();
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

                return redirect()->route('sanatorium.bill.bills.index');
            }
        }
        else
        {
            $bill = $this->bills->createModel();
        }

        $users = app('platform.users')->get();

        $clients = app('sanatorium.clients.client')->get();

        // Show the page
        return view('sanatorium/bill::create', compact('mode', 'bill', 'users', 'clients'));
    }

    public function download($filename)
    {
        $bill = app('sanatorium.bill.bill')->where('num', $filename)->first();

        $file = storage_path() . '/bills/' . $bill->year . '/' . $filename . '.pdf';

        return response()->download($file);

    }

    public function sendForm($invoice)
    {
        $bill = app('sanatorium.bill.bill')->where('num', $invoice)->first();

        $buyer = app('sanatorium.clients.client')->find($bill->buyer_id);

        return view('sanatorium/bill::send-mail', compact('bill', 'buyer'));
    }

    public function send($invoice)
    {
        $bill = app('sanatorium.bill.bill')->where('num', $invoice)->first();

        $object = request()->all();

        $file_path = storage_path() . '/bills/' . $bill->year . '/' . $bill->num . '.pdf';

        $attachments[] = $file_path;

        Event::fire('invoice', [ $object, $attachments ]);
    }

    public function show($invoice)
    {
        $bill = app('sanatorium.bill.bill')->where('num', $invoice)->first();

        $filename = $bill->num . '.pdf';

        $path = storage_path() . '/bills/' . $bill->year . '/' . $filename;

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);
    }

}
