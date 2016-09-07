<table class="payment_information-table">
    <tbody>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.issue_date' , [] , 'messages' , $lang) }}
        </td>
        <td style="text-align:right;">

            @if ( $lang == 'cs' )

                {{ $bill->issue_date->format('d. m. Y') }}

            @else

                {{ $bill->issue_date->format('Y-m-d') }}

            @endif

        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.due_date' , [] , 'messages' , $lang) }}
        </td>
        <td style="text-align:right;">

            @if ( $lang == 'cs' )

                {{ $bill->due_date->format('d. m. Y') }}

            @else

                {{ $bill->due_date->format('Y-m-d') }}

            @endif

        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.means_of_payment' , [] , 'messages' , $lang) }}
        </td>
        <td style="text-align:right;">

        <!-- @TODO Better translation of means of payment -->

            @if ( $lang == 'cs' )

                @if ( $bill->means_of_payment == 'Bank transfer' )

                    Převodem na účet

                @elseif ( $bill->means_of_payment == 'Cash' )

                    V hotovsti

                @endif

            @else

                {{ $bill->means_of_payment }}

            @endif

        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.payment_symbol' , [] , 'messages' , $lang) }}
        </td>
        <td style="text-align:right;">
            {{ $bill->payment_symbol }}
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.account_number' , [] , 'messages' , $lang) }}
        </td>
        <td style="text-align:right;">
            {{ $bill->account_number }}
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.iban' , [] , 'messages' , $lang) }}
        </td>
        <td style="text-align:right;">
            {{ $bill->iban }}
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.swift' , [] , 'messages' , $lang) }}
        </td>
        <td style="text-align:right;">
            {{ $bill->swift }}
        </td>
    </tr>
    </tbody>
</table>