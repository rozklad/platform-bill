@extends('sanatorium/bill::layout/default')

@section('bill')

    <div class="container">

        <table class="bill-list-table" style="width: 100%;">

            <thead>

            <th>Payment symbol</th>

            <th>Issue date</th>

            <th>Due date</th>

            <th>Buyer</th>

            <th>Total</th>

            <th></th>

            </thead>

            <tbody>

            @foreach ($bills as $bill)

                    <!--{{ $bill }}-->
            <tr>

                <td>

                    {{ $bill->payment_symbol }}

                </td>

                <td>

                    <?= date('d-m-Y', strtotime($bill->issue_date)) ?>

                </td>

                <td>

                    <?= date('d-m-Y', strtotime($bill->due_date)) ?>

                </td>

                <td class="download-icons">

                    <a href="{{ route('sanatorium.bill.bills.edit', ['id' => $bill->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                    <a href="<?= public_path() . '/bills/' . $bill->num . '.pdf' ?>" download><i class="fa fa-download" aria-hidden="true"></i></a>

                    <a href=""><i class="fa fa-envelope" aria-hidden="true"></i></a>

                </td>

            </tr>

            @endforeach

            </tbody>

        </table>

    </div>

@stop
