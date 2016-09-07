@extends('sanatorium/bill::layout/default')

@section('bill')

    <div class="container">

        <form method="POST">

            <div class="form-group">

                <label for="">Invoice</label>

                <h5>

                    <a class="dollar-green" href="{{ route('sanatorium.bill.bills.show', ['id' => $bill->num]) }}">

                        <i class="fa fa-file"></i> {{ $bill->num }}, {{ $buyer->name }}

                    </a>

                </h5>

            </div>

            <div class="form-group">

                <label for="">E-mail</label>

                <input class="form-control" type="email" name="email" required>

            </div>

            <div class="form-group">

                <label for="">Subject</label>

                <input class="form-control" type="text" name="subject" required>

            </div>

            <div class="form-group">

                <label for="">Text</label>

                <textarea class="form-control" rows="10" cols="50" name="text" required></textarea>

            </div>

            <button class="btn btn-block btn-dollar-green" type="submit">Send</button>

        </form>

    </div>

@stop
