@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{ trans('sanatorium/bill::bills/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('bootstrap-daterange', 'bootstrap/css/daterangepicker-bs3.css', 'style') }}

{{ Asset::queue('moment', 'moment/js/moment.js', 'jquery') }}
{{ Asset::queue('data-grid', 'cartalyst/js/data-grid.js', 'jquery') }}
{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}
{{ Asset::queue('index', 'sanatorium/bill::bills/js/index.js', 'platform') }}
{{ Asset::queue('bootstrap-daterange', 'bootstrap/js/daterangepicker.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

{{-- Money over time widget --}}
<div class="panel panel-default">
	<div class="panel-body">
		@widget('sanatorium/bill::bill.moneyOverTime')
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<?php

		$bills = \Sanatorium\Bill\Models\Bill::get();

		$buyers = [];

		$currencies_all = app('sanatorium.pricing.currency')->get();

		foreach ( $currencies_all as $currency_all ) {

			$currencies[strtoupper($currency_all->code)] = $currency_all->unit;

		}

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
						echo $client->name;
					?></th>
					<td>{{ $money }} Kč</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

{{-- Grid --}}
<section class="panel panel-default panel-grid">

	{{-- Grid: Header --}}
	<header class="panel-heading">

		<nav class="navbar navbar-default navbar-actions">

			<div class="container-fluid">

				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<span class="navbar-brand">{{{ trans('sanatorium/bill::bills/common.title') }}}</span>

				</div>

				{{-- Grid: Actions --}}
				<div class="collapse navbar-collapse" id="actions">

					<ul class="nav navbar-nav navbar-left">

						<li class="disabled">
							<a class="disabled" data-grid-bulk-action="disable" data-toggle="tooltip" data-original-title="{{{ trans('action.bulk.disable') }}}">
								<i class="fa fa-eye-slash"></i> <span class="visible-xs-inline">{{{ trans('action.bulk.disable') }}}</span>
							</a>
						</li>

						<li class="disabled">
							<a data-grid-bulk-action="enable" data-toggle="tooltip" data-original-title="{{{ trans('action.bulk.enable') }}}">
								<i class="fa fa-eye"></i> <span class="visible-xs-inline">{{{ trans('action.bulk.enable') }}}</span>
							</a>
						</li>

						<li class="danger disabled">
							<a data-grid-bulk-action="delete" data-toggle="tooltip" data-target="modal-confirm" data-original-title="{{{ trans('action.bulk.delete') }}}">
								<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.bulk.delete') }}}</span>
							</a>
						</li>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle tip" data-toggle="dropdown" role="button" aria-expanded="false" data-original-title="{{{ trans('action.export') }}}">
								<i class="fa fa-download"></i> <span class="visible-xs-inline">{{{ trans('action.export') }}}</span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li><a data-download="json"><i class="fa fa-file-code-o"></i> JSON</a></li>
								<li><a data-download="csv"><i class="fa fa-file-excel-o"></i> CSV</a></li>
								<li><a data-download="pdf"><i class="fa fa-file-pdf-o"></i> PDF</a></li>
							</ul>
						</li>

						<li class="primary">
							<a href="{{ route('admin.sanatorium.bill.bills.create') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.create') }}}">
								<i class="fa fa-plus"></i> <span class="visible-xs-inline">{{{ trans('action.create') }}}</span>
							</a>
						</li>

					</ul>

					{{-- Grid: Filters --}}
					<form class="navbar-form navbar-right" method="post" accept-charset="utf-8" data-search data-grid="bill" role="form">

						<div class="input-group">

							<span class="input-group-btn">

								<button class="btn btn-default" type="button" disabled>
									{{{ trans('common.filters') }}}
								</button>

								<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>

								<ul class="dropdown-menu" role="menu">

									<li>
										<a data-grid="bill" data-filter="enabled:1" data-label="enabled::{{{ trans('common.all_enabled') }}}" data-reset>
											<i class="fa fa-eye"></i> {{{ trans('common.show_enabled') }}}
										</a>
									</li>

									<li>
										<a data-toggle="tooltip" data-placement="top" data-original-title="" data-grid="bill" data-filter="enabled:0" data-label="enabled::{{{ trans('common.all_disabled') }}}" data-reset>
											<i class="fa fa-eye-slash"></i> {{{ trans('common.show_disabled') }}}
										</a>
									</li>

									<li class="divider"></li>

									<li>
										<a data-grid-calendar-preset="day">
											<i class="fa fa-calendar"></i> {{{ trans('date.day') }}}
										</a>
									</li>

									<li>
										<a data-grid-calendar-preset="week">
											<i class="fa fa-calendar"></i> {{{ trans('date.week') }}}
										</a>
									</li>

									<li>
										<a data-grid-calendar-preset="month">
											<i class="fa fa-calendar"></i> {{{ trans('date.month') }}}
										</a>
									</li>

								</ul>

								<button class="btn btn-default hidden-xs" type="button" data-grid-calendar data-range-filter="created_at">
									<i class="fa fa-calendar"></i>
								</button>

							</span>

							<input class="form-control " name="filter" type="text" placeholder="{{{ trans('common.search') }}}">

							<span class="input-group-btn">

								<button class="btn btn-default" type="submit">
									<span class="fa fa-search"></span>
								</button>

								<button class="btn btn-default" data-grid="bill" data-reset>
									<i class="fa fa-refresh fa-sm"></i>
								</button>

							</span>

						</div>

					</form>

				</div>

			</div>

		</nav>

	</header>

	<div class="panel-body">

		{{-- Grid: Applied Filters --}}
		<div class="btn-toolbar" role="toolbar" aria-label="data-grid-applied-filters">

			<div id="data-grid_applied" class="btn-group" data-grid="bill"></div>

		</div>

	</div>

	{{-- Grid: Table --}}
	<div class="table-responsive">

		<table id="data-grid" class="table table-hover" data-source="{{ route('admin.sanatorium.bill.bills.grid') }}" data-grid="bill">
			<thead>
				<tr>
					<th><input data-grid-checkbox="all" type="checkbox"></th>
					<th class="sortable" data-sort="id">{{{ trans('sanatorium/bill::bills/model.general.id') }}}</th>
					<th class="sortable" data-sort="num">{{{ trans('sanatorium/bill::bills/model.general.num') }}}</th>
					<th class="sortable" data-sort="issue_date">{{{ trans('sanatorium/bill::bills/model.general.issue_date') }}}</th>
					<th class="sortable" data-sort="due_date">{{{ trans('sanatorium/bill::bills/model.general.due_date') }}}</th>
					<th class="sortable" data-sort="means_of_payment">{{{ trans('sanatorium/bill::bills/model.general.means_of_payment') }}}</th>
					<th class="sortable" data-sort="payment_symbol">{{{ trans('sanatorium/bill::bills/model.general.payment_symbol') }}}</th>
					<th class="sortable" data-sort="account_number">{{{ trans('sanatorium/bill::bills/model.general.account_number') }}}</th>
					<th class="sortable" data-sort="iban">{{{ trans('sanatorium/bill::bills/model.general.iban') }}}</th>
					<th class="sortable" data-sort="swift">{{{ trans('sanatorium/bill::bills/model.general.swift') }}}</th>
					<th class="sortable" data-sort="buyer_id">{{{ trans('sanatorium/bill::bills/model.general.buyer_id') }}}</th>
					<th class="sortable" data-sort="supplier_id">{{{ trans('sanatorium/bill::bills/model.general.supplier_id') }}}</th>
					<th class="sortable" data-sort="year">{{{ trans('sanatorium/bill::bills/model.general.year') }}}</th>
					<th class="sortable" data-sort="created_at">{{{ trans('sanatorium/bill::bills/model.general.created_at') }}}</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>

	</div>

	<footer class="panel-footer clearfix">

		{{-- Grid: Pagination --}}
		<div id="data-grid_pagination" data-grid="bill"></div>

	</footer>

	{{-- Grid: templates --}}
	@include('sanatorium/bill::bills/grid/index/results')
	@include('sanatorium/bill::bills/grid/index/pagination')
	@include('sanatorium/bill::bills/grid/index/filters')
	@include('sanatorium/bill::bills/grid/index/no_results')

</section>

@if (config('platform.app.help'))
	@include('sanatorium/bill::bills/help')
@endif

@stop
