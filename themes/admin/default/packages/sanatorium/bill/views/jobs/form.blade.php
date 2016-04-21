@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/bill::jobs/common.title') }}
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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.bill.jobs.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $job->exists ? $job->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($job->exists)
							<li>
								<a href="{{ route('admin.sanatorium.bill.jobs.delete', $job->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
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
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/bill::jobs/common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('sanatorium/bill::jobs/common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="form-group{{ Alert::onForm('bill_id', ' has-error') }}">

									<label for="bill_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::jobs/model.general.bill_id_help') }}}"></i>
										{{{ trans('sanatorium/bill::jobs/model.general.bill_id') }}}
									</label>

									<input type="text" class="form-control" name="bill_id" id="bill_id" placeholder="{{{ trans('sanatorium/bill::jobs/model.general.bill_id') }}}" value="{{{ input()->old('bill_id', $job->bill_id) }}}">

									<span class="help-block">{{{ Alert::onForm('bill_id') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('quantity', ' has-error') }}">

									<label for="quantity" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::jobs/model.general.quantity_help') }}}"></i>
										{{{ trans('sanatorium/bill::jobs/model.general.quantity') }}}
									</label>

									<input type="text" class="form-control" name="quantity" id="quantity" placeholder="{{{ trans('sanatorium/bill::jobs/model.general.quantity') }}}" value="{{{ input()->old('quantity', $job->quantity) }}}">

									<span class="help-block">{{{ Alert::onForm('quantity') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('description', ' has-error') }}">

									<label for="description" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::jobs/model.general.description_help') }}}"></i>
										{{{ trans('sanatorium/bill::jobs/model.general.description') }}}
									</label>

									<input type="text" class="form-control" name="description" id="description" placeholder="{{{ trans('sanatorium/bill::jobs/model.general.description') }}}" value="{{{ input()->old('description', $job->description) }}}">

									<span class="help-block">{{{ Alert::onForm('description') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('price', ' has-error') }}">

									<label for="price" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::jobs/model.general.price_help') }}}"></i>
										{{{ trans('sanatorium/bill::jobs/model.general.price') }}}
									</label>

									<input type="text" class="form-control" name="price" id="price" placeholder="{{{ trans('sanatorium/bill::jobs/model.general.price') }}}" value="{{{ input()->old('price', $job->price) }}}">

									<span class="help-block">{{{ Alert::onForm('price') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('currency', ' has-error') }}">

									<label for="currency" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/bill::jobs/model.general.currency_help') }}}"></i>
										{{{ trans('sanatorium/bill::jobs/model.general.currency') }}}
									</label>

									<input type="text" class="form-control" name="currency" id="currency" placeholder="{{{ trans('sanatorium/bill::jobs/model.general.currency') }}}" value="{{{ input()->old('currency', $job->currency) }}}">

									<span class="help-block">{{{ Alert::onForm('currency') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@attributes($job)
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
