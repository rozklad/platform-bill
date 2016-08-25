<?php namespace Sanatorium\Bill\Widgets;

use Carbon\Carbon;
use Platform\Foundation\Controllers\Controller;

class Frontendbill extends Controller {

    public function moneyOverTime()
    {
        return view('sanatorium/bill::widgets/money_over_time');
    }

    public function data($date_column = 'due_date')
    {
        $months = request()->has('months') ? request()->get('months') : null;
        $date_column = request()->has('date_column') ? request()->get('date_column') : $date_column;

        $max = 0;

        $d3data = [
            'min'   => 0,
            'max'   => $max,
            'lines' => [
                [
                    'key'    => 'Income',
                    'values' => [],
                    'area'   => true,
                ],
            ],
        ];

        $start = $months ? Carbon::now()->subMonths($months) : self::getEarliestBill()->{$date_column};

        $start = $start->startOfMonth();
        $end = Carbon::now();

        $repo = app('Sanatorium\Bill\Repositories\Bill\BillRepositoryInterface');

        do
        {
            $money = 0;

            $bills = $repo->where($date_column, '>=', $start->format('Y-m-d'))
                ->where($date_column, '<=', $start->format('Y-m-').'31')
                ->get();

            // @todo: automatically resolve currencies
            $currencies = [
                'Kč' => 1,
                'EUR' => 27,
                'CHF' => 25
            ];

            foreach( $bills as $bill ) {

                foreach( $bill->jobs() as $job ) {

                    if ( isset($currencies[$job->currency]) )
                    {
                        $money = $money + $job->price * $currencies[ $job->currency ];
                    } else {

                        throw new \Exception('Currency not set ' . $job->currency);

                    }

                }

            }

            $d3data['lines'][0]['values'][] = [
                $start->timestamp * 1000,
                $money,
            ];

            if ( $money > $max ) {
                $max = $money;
            }

        } while ($start->addMonth() <= $end);

        $d3data['max'] = $max;

        return $d3data;
    }

    public static function getEarliestBill()
    {
        $repo = app('Sanatorium\Bill\Repositories\Bill\BillRepositoryInterface');

        return $repo->orderBy('due_date', 'ASC')->first();
    }

}
