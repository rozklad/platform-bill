@extends('sanatorium/bill::layout/default')

@section('scripts')
    @parent
    <script type="text/javascript">

        $(function(){

            $('[name="year"]').change(function(event){

                var year = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: "{{ route('sanatorium.bill.bills.revenue') }}",
                    data: {year: year}
                }).success(function(data){

                    alert(data);

                }).error(function(data){

                });

            });

        });

    </script>

@stop

@section('bill')

    <div class="container">

        @widget('sanatorium/bill::frontendbill.moneyOverTime')

        <div class="panel-body">

            <div class="row">
                <div class="form-group">
                    Vypocet prijmu za rok: {{-- @todo: lang --}}
                    <input type="text" class="form-control" name="year">
                </div>
            </div>

            <?php

            $buyers = [];

            /*
                $test = [];

            foreach ( $currencies as $currency ) {

                $test[][$currency->code] = $currency->unit;

            }

            $currencies = [
                    'Kč' => 1,
                    'EUR' => 27,
                    'CHF' => 25
            ];*/

            foreach( $bills as $bill ) {

                $money = 0;

                foreach( $bill->jobs as $job )
                {

                    if ( isset($currencies[$job->currency]) )
                    {
                        $money = $job->price * $currencies[ $job->currency ];
                    } else {
                        throw new \Exception('Currency not set ' . $job->currency);
                    }

                }

                if ( isset($buyers[$bill->buyer_id]) ) {
                    $buyers[$bill->buyer_id] = $buyers[$bill->buyer_id] + $money;
                } else {
                    $buyers[$bill->buyer_id] = $money;
                }
            }

            arsort($buyers);

            ?>

        </div>
        <table class="table">
            <tbody>
            @foreach( $buyers as $buyer_id => $money )
                <tr>
                    <th>
                        <?php
                        $client = Sanatorium\Clients\Models\Client::find($buyer_id);
                        if ( is_object($client) ) {
                            echo $client->name;
                        }
                        ?></th>
                    <td>{{ $money }} Kč</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@stop
