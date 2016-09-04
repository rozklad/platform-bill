@extends('sanatorium/bill::layout/default')

@section('bill')

@section('scripts')

    <script type="text/javascript">

        $(function(){

            $('.paid-button').on('click', function(){

                var billNum = $(this).attr('bill-num');

                var clientName = $(this).attr('client-name');

                var billId = $(this).attr('bill-id');

                $('#paidModalTitle').text('Invoice: ' + billNum + ', ' + clientName);

                $('#modalBillId').val(billId);

                $('.paid-modal').fadeIn('slow');

            });

            $('#closeButton').on('click', function () {

                $('.paid-modal').fadeOut('slow');

            })

        });

    </script>

@stop

@include('sanatorium/bill::partials/paid-modal')

    <div class="container">

        <table class="bill-list-table" style="width: 100%;">

            <thead>

            <th>Payment symbol</th>

            <th>Issue date</th>

            <th>Due date</th>

            <th>Buyer</th>

            <th>Total</th>

            <th>Sent?</th>

            <th>Paid?</th>

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

                        @foreach ( $bill->jobs as $job )

                            <?php array_push($totalPrice, $job->price); ?>

                        @endforeach

                        {{ array_sum($totalPrice) }} {{ $bill->jobs()->first()->currency  }}

                    </td>

                    <td>

                        @if ( $bill->sent )

                            <span class="dollar-green">

                            {{ $bill->sent }}

                            </span>

                        @else

                            <span class="danger">

                            Not yet

                            </span>

                        @endif

                    </td>

                    <td>

                        @if ( $bill->paid )

                            <span class="dollar-green">

                            {{ $bill->paid }}

                            </span>

                        @else

                            <span class="danger">

                            Not yet

                            </span>

                        @endif

                    </td>

                    <td class="download-icons">

                        <a href="{{ route('sanatorium.bill.bills.edit', ['id' => $bill->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                        <span class="paid-button" bill-id="{{ $bill->id }}" bill-num="{{ $bill->num }}" client-name="{{ $client->name }}"><i class="fa fa-usd" aria-hidden="true"></i></span>

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
