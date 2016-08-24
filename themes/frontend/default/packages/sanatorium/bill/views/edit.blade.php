@extends('sanatorium/bill::layout/default')

@section('bill')

@section('scripts')

    <!-- <div class="row" data-job-number="' + job_number + '"><div class="form-group"><input type="text" id="bill_id" name="jobs[' + job_number + '][bill_id]" hidden value=""><div class="col-sm-1"><input required class="form-control" type="number" id="jobs.quantity" name="jobs[' + job_number + '][quantity]" value="1"></div><div class="col-sm-6"><input required class="form-control" type="text" id="description" name="jobs[' + job_number + '][description]" placeholder="Description"></div><div class="col-sm-2"><input required class="form-control" type="number" id="price" name="jobs[' + job_number + '][price]" placeholder="Price"></div><div class="col-sm-2"><select class="form-control" id="currency" name="jobs[' + job_number + '][currency]"><option value="Kč">Kč</option><option value="EUR">EUR</option><option value="CHF">CHF</option></select></div></div></div> -->

    <script>

        $(function () {

            var job_number = 1;

            $('#more_jobs').click(function () {

                //$(".row[data-job-number='" + (job_number - 1) + "']").after();

                $(".row[data-job-number]").last().after(@include('sanatorium/bill::partials/job'));

                job_number++;

            });

            $(document).on('click', '#delete_job', function() {

                $(this).parent().parent().parent().remove();

                job_number--;

            });



        });

    </script>

@stop

<div class="container">

    <form method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input type="text" id="num" name="bill[0][num]" value="{{ $num }}" hidden>

        <div class="row">

            <!-- Left side -->

            <div class="col-sm-6">

                <!-- Issue date -->

                <div class="form-group">

                    <label for="issue_date">Issue date</label>

                    <input class="form-control" type="date" id="issue_date" name="bill[0][issue_date]"
                           value="{{ $issue_date }}">

                </div>

                <!-- Means of payment -->

                <div class="form-group">

                    <!--<label for="means_of_payment">Means of payment</label>-->

                    <select class="form-control" name="bill[0][means_of_payment]" id="means_of_payment">

                        <option value="Bank transfer">Bank transfer</option>

                        <option value="Cash">Cash</option>

                    </select>

                    <!-- <input required type="text" class="form-control" id="means_of_payment" name="bill[0][means_of_payment]">-->

                </div>

                <!-- Supplier ID -->

                <div class="form-group">

                    <!--<label for="supplier_id">Supplier</label>-->

                    <select class="form-control" name="bill[0][supplier_id]" id="supplier_id">

                        @foreach ( $suppliers as $supplier )

                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>

                        @endforeach

                    </select>

                </div>

                <!-- IBAN -->

                <div class="form-group">

                    <!--<label for="iban">IBAN</label>-->

                    <input type="text" class="form-control" id="iban" name="bill[0][iban]" placeholder="IBAN">

                </div>

                <!-- Buyer ID -->

                <div class="form-group">

                    <!--<label for="buyer_id">Buyer</label>-->

                    <select class="form-control" name="bill[0][buyer_id]" id="buyer_id">

                        @foreach ( $buyers as $buyer )

                            <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>

                        @endforeach

                    </select>

                </div>

            </div>

            <!-- Right side -->

            <div class="col-sm-6">

                <!-- Due date -->

                <div class="form-group">

                    <label for="due_date">Due date</label>

                    <input class="form-control" type="date" id="due_date" name="bill[0][due_date]"
                           value="{{ $due_date }}">

                </div>

                <!-- Payment symbol -->

                <div class="form-group">

                    <!--<label for="payment_symbol">Payment symbol</label>-->

                    <input required type="text" class="form-control" id="payment_symbol" name="bill[0][payment_symbol]" placeholder="Payment symbol" value="{{ $num }}">

                </div>

                <!-- Account number -->

                <div class="form-group">

                    <!--<label for="account_number">Account number</label>-->

                    <input required type="text" class="form-control" id="account_number" name="bill[0][account_number]" placeholder="Account number">

                </div>

                <!-- SWIFT -->

                <div class="form-group">

                    <!--<label for="swift">SWIFT</label>-->

                    <input type="text" class="form-control" id="swift" name="bill[0][swift]" placeholder="SWIFT">

                </div>

                <input type="text" id="year" name="bill[0][year]" value="<?= date('Y') ?>" hidden>

            </div>

            <!-- Jobs -->

            <div class="col-sm-12 jobs-col">

                <!-- Single Job -->

                <div class="row" data-job-number="0" style="padding-bottom: 10px;">

                    <div class="form-group ">

                        <input type="text" id="bill_id" name="jobs[0][bill_id]" hidden value="">

                        <!--<label for="quantity">Quantity</label>-->

                        <div class="col-sm-1">

                            <input required class="form-control" type="number" id="jobs.quantity" name="jobs[0][quantity]" value="1">

                        </div>

                        <!--<label for="description">Job description</label>-->

                        <div class="col-sm-6">

                            <input required class="form-control" type="text" id="description" name="jobs[0][description]" placeholder="Description">

                        </div>

                        <!--<label for="price">Price</label>-->

                        <div class="col-sm-2">

                            <input required class="form-control" type="number" id="price" name="jobs[0][price]" placeholder="Price">

                        </div>

                        <!--<label for="currency">Currency</label>-->

                        <div class="col-sm-2">

                            <select class="form-control" id="currency" name="jobs[0][currency]">

                                <option value="Kč">Kč</option>

                                <option value="EUR">EUR</option>

                                <option value="CHF">CHF</option>

                            </select>

                        </div>

                        <div class="col-sm-1 buttons-col">

                            <span class="circle-button add" id="more_jobs">+</span>

                        </div>

                    </div>

                </div>

                <!--<span class="btn btn-block btn-dollar-green" id="more_jobs">More jobs</span>-->

                <button class="btn btn-block btn-dollar-green" type="submit">Save & Generate PDF</button>

            </div>

        </div>

    </form>

</div>

@stop