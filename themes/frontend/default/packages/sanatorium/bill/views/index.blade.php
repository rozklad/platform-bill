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

                    <td>

                        <?php

                        $client = app('sanatorium.clients.client')->where('id', $bill->buyer_id)->first();

                        ?>

                        {{ $client->name }}

                    </td>

                    <td>

                        <?php $totalPrice = []; ?>

                        @foreach ( $bill->jobs() as $job )

                                <?php array_push($totalPrice, $job->price); ?>

                        @endforeach

                            {{ array_sum($totalPrice) }} {{ $bill->jobs()->first()->currency  }}

                    </td>

                    <td class="download-icons">

                        <a href="{{ route('sanatorium.bill.bills.edit', ['id' => $bill->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                        <a href="{{ route('sanatorium.bill.bills.show', ['id' => $bill->num]) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>

                        <a href="{{ route('sanatorium.bill.bills.download', ['id' => $bill->num]) }}"><i class="fa fa-download" aria-hidden="true"></i></a>

                        <a href="{{ route('sanatorium.bill.bills.send', ['invoice' => $bill->num]) }}"><i class="fa fa-envelope" aria-hidden="true"></i></a>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

@stop
