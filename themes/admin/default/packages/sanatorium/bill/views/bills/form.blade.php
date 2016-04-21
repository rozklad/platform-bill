@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/bill::bills/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

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

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="bill-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate>

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.bill.bills.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $bill->exists ? $bill->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($bill->exists)
							<li>
								<a href="{{ route('admin.sanatorium.bill.bills.delete', $bill->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/bill::bills/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/bill::bills/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('num', ' has-error') }}">

									<label for="num" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.num_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.num') }}}
									</label>

									<input type="text" class="form-control" name="num" id="num" placeholder="{{{ trans('sanatorium/bill::bills/model.general.num') }}}" value="{{{ input()->old('num', $bill->num) }}}">

									<span class="help-block">{{{ Alert::onForm('num') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('issue_date', ' has-error') }}">

									<label for="issue_date" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.issue_date_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.issue_date') }}}
									</label>

									<input type="text" class="form-control" name="issue_date" id="issue_date" placeholder="{{{ trans('sanatorium/bill::bills/model.general.issue_date') }}}" value="{{{ input()->old('issue_date', $bill->issue_date) }}}">

									<span class="help-block">{{{ Alert::onForm('issue_date') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('due_date', ' has-error') }}">

									<label for="due_date" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.due_date_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.due_date') }}}
									</label>

									<input type="text" class="form-control" name="due_date" id="due_date" placeholder="{{{ trans('sanatorium/bill::bills/model.general.due_date') }}}" value="{{{ input()->old('due_date', $bill->due_date) }}}">

									<span class="help-block">{{{ Alert::onForm('due_date') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('means_of_payment', ' has-error') }}">

									<label for="means_of_payment" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.means_of_payment_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.means_of_payment') }}}
									</label>

									<input type="text" class="form-control" name="means_of_payment" id="means_of_payment" placeholder="{{{ trans('sanatorium/bill::bills/model.general.means_of_payment') }}}" value="{{{ input()->old('means_of_payment', $bill->means_of_payment) }}}">

									<span class="help-block">{{{ Alert::onForm('means_of_payment') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('payment_symbol', ' has-error') }}">

									<label for="payment_symbol" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.payment_symbol_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.payment_symbol') }}}
									</label>

									<input type="text" class="form-control" name="payment_symbol" id="payment_symbol" placeholder="{{{ trans('sanatorium/bill::bills/model.general.payment_symbol') }}}" value="{{{ input()->old('payment_symbol', $bill->payment_symbol) }}}">

									<span class="help-block">{{{ Alert::onForm('payment_symbol') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('account_number', ' has-error') }}">

									<label for="account_number" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.account_number_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.account_number') }}}
									</label>

									<input type="text" class="form-control" name="account_number" id="account_number" placeholder="{{{ trans('sanatorium/bill::bills/model.general.account_number') }}}" value="{{{ input()->old('account_number', $bill->account_number) }}}">

									<span class="help-block">{{{ Alert::onForm('account_number') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('iban', ' has-error') }}">

									<label for="iban" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.iban_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.iban') }}}
									</label>

									<input type="text" class="form-control" name="iban" id="iban" placeholder="{{{ trans('sanatorium/bill::bills/model.general.iban') }}}" value="{{{ input()->old('iban', $bill->iban) }}}">

									<span class="help-block">{{{ Alert::onForm('iban') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('swift', ' has-error') }}">

									<label for="swift" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.swift_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.swift') }}}
									</label>

									<input type="text" class="form-control" name="swift" id="swift" placeholder="{{{ trans('sanatorium/bill::bills/model.general.swift') }}}" value="{{{ input()->old('swift', $bill->swift) }}}">

									<span class="help-block">{{{ Alert::onForm('swift') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('buyer_id', ' has-error') }}">

									<label for="buyer_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.buyer_id_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.buyer_id') }}}
									</label>

									<input type="text" class="form-control" name="buyer_id" id="buyer_id" placeholder="{{{ trans('sanatorium/bill::bills/model.general.buyer_id') }}}" value="{{{ input()->old('buyer_id', $bill->buyer_id) }}}">

									<span class="help-block">{{{ Alert::onForm('buyer_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('supplier_id', ' has-error') }}">

									<label for="supplier_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.supplier_id_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.supplier_id') }}}
									</label>

									<input type="text" class="form-control" name="supplier_id" id="supplier_id" placeholder="{{{ trans('sanatorium/bill::bills/model.general.supplier_id') }}}" value="{{{ input()->old('supplier_id', $bill->supplier_id) }}}">

									<span class="help-block">{{{ Alert::onForm('supplier_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('year', ' has-error') }}">

									<label for="year" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::bills/model.general.year_help') }}}"></i>
										{{{ trans('sanatorium/bill::bills/model.general.year') }}}
									</label>

									<input type="text" class="form-control" name="year" id="year" placeholder="{{{ trans('sanatorium/bill::bills/model.general.year') }}}" value="{{{ input()->old('year', $bill->year) }}}">

									<span class="help-block">{{{ Alert::onForm('year') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($bill)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
