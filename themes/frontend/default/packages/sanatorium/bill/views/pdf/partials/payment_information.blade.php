<table class="payment_information-table">
    <tbody>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.issue_date') }}
        </td>
        <td style="text-align:right;">
            {{ $bill->issue_date }}
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.due_date') }}
        </td>
        <td style="text-align:right;">
            {{ $bill->due_date }}
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.means_of_payment') }}
        </td>
        <td style="text-align:right;">
            {{ $bill->means_of_payment }}
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.payment_symbol') }}
        </td>
        <td style="text-align:right;">
            {{ $bill->payment_symbol }}
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.account_number') }}
        </td>
        <td style="text-align:right;">
            {{ $bill->account_number }}
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.iban') }}
        </td>
        <td style="text-align:right;">
            {{ $bill->iban }}
        </td>
    </tr>
    <tr>
        <td>
            {{ trans('sanatorium/bill::bills/pdf.template.swift') }}
        </td>
        <td style="text-align:right;">
            {{ $bill->swift }}
        </td>
    </tr>
    </tbody>
</table>