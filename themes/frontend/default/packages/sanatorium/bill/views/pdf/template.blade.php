<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Invoice</title>

    <style type="text/css">
        /* sizes */
        body {
            font-family: 'Helvetica Neue', sans-serif;
        }
        table {
            width: 100%;
        }
        body, td {
            font-size: 12px;
            line-height: 16px;
            color: #666;
        }
        h2 {
            font-size: 14px;
            font-weight: 400;
        }
        .muted {
            color: #999;
            font-size: 8px;
        }
        .header td {
            height: 240px;
        }

        .mid>td {
            height: 442px;
        }
        .top-bumper td {
            height: 20px;
        }
        .bumper td {
            height: 40px;
        }
        .job-table {
            font-size: 12px;
        }
        .job-table th {
            font-weight: bold;
            text-align: left;
        }
        .job-table td {
            border-bottom: 1px solid #ccc;
            vertical-align: middle;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .payment_information-table {
            width: 50%;
        }
        .footer {
            left: 5%;
            bottom: 0;
            right: 5%;
            text-align: center;
            top: auto;
            position: fixed;
            width: 90%;
        }

    </style>
</head>

<body>

<table>
    <tbody>
    <tr class="header">
        <td width="5%">
        </td>
        <td width="60%">
            <h2>Invoice {{ $bill->payment_symbol }}</h2>
            @include('sanatorium/bill::pdf/partials/buyer')
        </td>
        <td width="30%">
            @include('sanatorium/bill::pdf/partials/supplier')
        </td>
        <td width="5%">
        </td>
    </tr>
    <tr class="mid">
        <td width="5%">
        </td>
        <td width="90%" colspan="2">
            @include('sanatorium/bill::pdf/partials/content')
            <br>
            <strong style="float:right;font-size:16px;text-align:right;">
                <?php $totalPrice = []; ?>
                @foreach( $jobs as $item )
                    <?php array_push($totalPrice, $item->price); ?>
                @endforeach
                {{ array_sum($totalPrice) }} {{ $jobs->first()->currency }}
            </strong>
            <br><br><br>
            @include('sanatorium/bill::pdf/partials/payment_information')
        </td>
        <td width="5%">
        </td>
    </tr>
    <tr class="bumper">
        <td colspan="4">
        </td>
    </tr>
    </tbody>
</table>

<footer class="footer">
    @include('sanatorium/bill::pdf/partials/footer')
</footer>

</body>

</html>