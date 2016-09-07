<h2>{{ trans('sanatorium/bill::bills/pdf.template.supplier' , [] , 'messages' , $lang) }}</h2>
<strong>{{ $supplier->name }}</strong><br>
{!! nl2br( $supplier->client_address ) !!}<br>
<br>
{{ $supplier->tax_id }}<br>
{{ $supplier->vat_id }}<br>