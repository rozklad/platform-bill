@extends('layouts/default')

@section('scripts')

    <script>

        $(function(){

            var job_number = 1;

            $('#more_jobs').click(function(){

                $(".input-group[data-job-number='" + (job_number - 1) + "']").after('<div class="input-group" data-job-number="' + job_number + '"><input type="text" id="bill_id" name="jobs[' + job_number + '][bill_id]" hidden value="{{ $bill->id  }}"><label for="quantity">Quantity</label> <input type="number" id="jobs.quantity" name="jobs[' + job_number + '][quantity]"> <label for="description">Description</label><input type="text" id="description" name="jobs[' + job_number + '][description]"><label for="price">Price</label><input type="number" id="price" name="jobs[' + job_number + '][price]"><label for="currency">Currency</label><input type="text" id="currency" name="jobs[' + job_number + '][currency]"></div>');

                job_number++;

            });

        });

    </script>

@stop

@section('page')

    <div class="container">

        <form method="post">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <input type="text" id="num" name="bill[0][num]" value="{{ $bill->num }}" hidden>

            <label for="issue_date">Issue date</label>

            <input type="date" id="issue_date" name="bill[0][issue_date]" value="{{ $bill->issue_date }}">

            <label for="due_date">Due date</label>

            <input type="date" id="due_date" name="bill[0][due_date]" value="{{ $bill->due_date }}">

            <label for="means_of_payment">Means of payment</label>

            <input type="text" id="means_of_payment" name="bill[0][means_of_payment]"
                   value="{{ $bill->means_of_payment }}">

            <label for="payment_symbol">Payment symbol</label>

            <input type="text" id="payment_symbol" name="bill[0][payment_symbol]" value="{{ $bill->payment_symbol }}">

            <label for="account_number">Account number</label>

            <input type="text" id="account_number" name="bill[0][account_number]" value="{{ $bill->account_number }}">

            <label for="iban">IBAN</label>

            <input type="text" id="iban" name="bill[0][iban]" value="{{ $bill->iban }}">

            <label for="swift">SWIFT</label>

            <input type="text" id="swift" name="bill[0][swift]" value="{{ $bill->swift }}">

            <label for="buyer_id">Buyer</label>

            <select name="bill[0][buyer_id]" id="buyer_id">

                @foreach ( $clients as $client )

                    <option value="{{ $client->id }}">{{ $client->name }}</option>

                @endforeach

            </select>

            <label for="supplier_id">Supplier</label>

            <select name="bill[0][supplier_id]" id="supplier_id">

                @foreach ( $clients as $client )

                    @if ( $client->supplier )

                    <option value="{{ $client->id }}">{{ $client->name }}</option>

                    @endif

                @endforeach

            </select>

            <input type="text" id="year" name="bill[0][year]" value="<?= date('Y') ?>" hidden>

            <h5>Jobs</h5>

            <div class="input-group" data-job-number="0">

                <input type="text" id="bill_id" name="jobs[0][bill_id]" hidden value="{{ $bill->id  }}">

                <label for="quantity">Quantity</label>

                <input type="number" id="jobs.quantity" name="jobs[0][quantity]">

                <label for="description">Description</label>

                <input type="text" id="description" name="jobs[0][description]">

                <label for="price">Price</label>

                <input type="number" id="price" name="jobs[0][price]">

                <label for="currency">Currency</label>

                <input type="text" id="currency" name="jobs[0][currency]">

            </div>

            <span class="btn btn-primary" id="more_jobs">More jobs</span>

            <button type="submit">Posli</button>

        </form>

    </div>

@stop