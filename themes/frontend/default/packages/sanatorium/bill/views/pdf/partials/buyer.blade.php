<h2>{{ trans('sanatorium/bill::bills/pdf.template.buyer' , [] , 'messages' , $lang)  }}</h2>
<strong>{{ $buyer->name }}</strong><br>
{!! nl2br( $buyer->client_address ) !!}<br>
<br>
{{ $buyer->tax_id }}<br>
{{ $buyer->vat_id }}<br>