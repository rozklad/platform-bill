<table width="100%" class="job-table">
    <thead>
    <tr>
        <th>
            {{ trans('sanatorium/bill::bills/pdf.template.description') }}
        </th>
        <th style="text-align:right">
            {{ trans('sanatorium/bill::bills/pdf.template.total') }}
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach( $jobs as $item )
        <tr>
            <td>

                    <strong>{{ $item->description }}</strong><br>


                {{ trans('sanatorium/bill::bills/pdf.template.quantity') }}: {{ $item->quantity }}

            </td>
            <td style="text-align:right">

                    {{ $item->price }} {{ $item->currency }}

            </td>
        </tr>
    @endforeach
    </tbody>
</table>