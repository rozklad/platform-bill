@extends('layouts/default')

@section('page')

<div class="container">

	<a href="{{ route('sanatorium.bill.bills.new') }}">Nový</a>

	<div class="row">
		
		Bills

		<table style="width: 100%;">
			
			<thead>
				
				<th>Bill number</th>

				<th>Issue date</th>

				<th>Due date</th>

			</thead>

			<tbody>
				
				@foreach ($bills as $bill)

				<tr>

					<td>

						{{ $bill->num }}

					</td>

					<td>
						
						<?= date('d/m/Y', strtotime($bill->issue_date)) ?>

					</td>

					<td>
						
						<?= date('d/m/Y', strtotime($bill->due_date)) ?>

					</td>

				</tr>

				@endforeach

			</tbody>

		</table>

	</div>

</div>

@stop