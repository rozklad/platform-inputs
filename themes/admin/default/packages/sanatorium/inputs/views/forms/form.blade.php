@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/inputs::forms/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
	<script type="text/javascript">
		window.settings = {!! ($form->settings ? $form->settings : '[]') !!};
		window.currentActiveTab = 0;

		function buildTabs(settings) {
			var html = _.template( $('#tabSettings').html() )( {settings: settings} );
			return html;
		}

		function getTab(label) {

			if ( typeof label == 'undefined' || label == '' ) {
				label = 'Unnamed tab';
			}

			return {
				'label'				: label,
				'groups' 			: [],
				'conditional_logic' : []
			};
		}

		function setTabsFromJSON() {

			var html = buildTabs(window.settings);
			$('#preview').html(html);

			activateTabControls();

			$('#tab-settings li:eq('+window.currentActiveTab+') a').tab('show');

		}

		function activateTabControls() {

			$('[data-add-tab]').click(function(event){
				event.preventDefault();

				var label = prompt("Label for new tab");

				var tab = getTab(label);

				window.settings.push(tab);

				setTabsFromJSON();
			});

			$('[data-add-group]').click(function(event){
				event.preventDefault();

				var index = $(this).data('index');

				if ( typeof window.settings[index].groups == 'undefined' )
					window.settings[index].groups = [];

				window.settings[index].groups.push({
					id: null,
					name: null
				});

				setTabsFromJSON();
			});

			$('[data-tab-label]').change(function(event){

				var index = $(this).data('index');

				window.settings[index].label = $(this).val();

			});

			$('[data-group-id]').change(function(event){

				var index = $(this).data('index'),
					groupindex = $(this).data('groupindex');

				window.settings[index].groups[groupindex] = $(this).val();

			});

		}

		$(function(){

			setTabsFromJSON();

			$('#tab-settings a').click(function (event) {
				window.currentActiveTab = $(this).parents('li:first').index();
			});

		});
	</script>
@stop

{{-- Inline styles --}}
@section('styles')
@parent
	<style type="text/css">
		[data-tab-label] {
			text-align: center;
			border-color: transparent;
			outline: 0;
		}
	</style>
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="inputs-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate>

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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.inputs.forms.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $form->exists ? $form->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($form->exists)
							<li>
								<a href="{{ route('admin.sanatorium.inputs.forms.delete', $form->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
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
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/inputs::forms/common.tabs.general') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset class="col-sm-12">

							<div class="row">

								<legend>
									{{{ trans('sanatorium/inputs::forms/common.tabs.general') }}}
								</legend>

								<div class="form-group{{ Alert::onForm('entity', ' has-error') }}">

									<label for="entity" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/inputs::forms/model.general.entity_help') }}}"></i>
										{{{ trans('sanatorium/inputs::forms/model.general.entity') }}}
									</label>

									<select class="form-control" name="entity" id="entity" placeholder="{{{ trans('sanatorium/inputs::forms/model.general.entity') }}}" value="{{{ input()->old('entity', $form->entity) }}}">
										@foreach( $entities as $model => $name )
											<option value="{{ $model }}">{{ $name }}</option>
										@endforeach
									</select>

									<span class="help-block">{{{ Alert::onForm('entity') }}}</span>

								</div>

								<div class="form-group{{ Alert::onForm('settings', ' has-error') }}">

									<label for="settings" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/inputs::forms/model.general.settings_help') }}}"></i>
										{{{ trans('sanatorium/inputs::forms/model.general.settings') }}}
									</label>

									<div id="preview"></div>

									<span class="help-block">{{{ Alert::onForm('settings') }}}</span>

								</div>


							</div>

						</fieldset>

					</div>

				</div>

			</div>

		</div>

	</form>

</section>


<script type="text/html" id="tabSettings">

	<ul class="nav nav-tabs" role="tablist" id="tab-settings">
		<% _.each(settings, function(tab, index) { %>
		<li role="presentation" class="<%= (index == 0 ? 'active' : '') %>">
			<a href="#tab<%= index %>" role="tab" data-toggle="tab">
				<input type="text" name="settings[<%= index %>][label]" value="<%= tab.label %>" data-tab-label data-index="<%= index %>">
			</a>
		</li>
		<% }); %>
		<li role="presentation">
			<a href="#" data-add-tab>
				<i class="fa fa-plus" aria-hidden="true"></i>
			</a>
		</li>
	</ul>

	<div class="tab-content">
		<% _.each(settings, function(tab, index) { %>
			<div role="tabpanel" class="tab-pane <%= (index == 0 ? 'active' : '') %>" id="tab<%= index %>">
				<% _.each(tab.groups, function(group, groupindex) { %>
					<div class="form-group">
						<select name="settings[<%= index %>][groups][<%= groupindex %>]" class="form-control" data-index="<%= index %>" data-groupindex="<%= groupindex %>" data-group-id>
							<option value="general" <%= (group == 'general' ? 'selected' : '') %>>General</option>
							<option value="tags" <%= (group == 'tags' ? 'selected' : '') %>>Tags</option>
							<option value="files" <%= (group == 'files' ? 'selected' : '') %>>Files</option>
							<option value="rss" <%= (group == 'rss' ? 'selected' : '') %>>RSS</option>
							<option value="twitter" <%= (group == 'twitter' ? 'selected' : '') %>>Twitter stream</option>
							@foreach( $groups as $group )
								<option value="{{ $group->id }}" <%= (group == '{{ $group->id }}' ? 'selected' : '') %>>{{ $group->name }}</option>
							@endforeach
						</select>
					</div>
				<% }); %>

				<a href="#" class="btn btn-default" data-add-group data-index="<%= index %>">
					<i class="fa fa-plus-circle" aria-hidden="true"></i> {{ trans('action.add') }}
				</a>

				<hr>

				<div class="well">
					<h5>Conditional logic</h5>
					@foreach( $conditionals as $key => $attribute )
						<label class="control-label">
							{{ $attribute['attribute']->name }}
						</label>
						<select name="settings[<%= index %>][conditional_logic][{{ $key }}]" class="form-control">
							<option value="*">{{ trans('common.all') }}</option>
							@foreach( $attribute['options'] as $option_key => $option_label )
								<option value="{{ $option_key }}" <%= (typeof tab.conditional_logic !== 'undefined' ? (tab.conditional_logic.{{ $key }} == '{{ $option_key }}' ? 'selected' : '') : '') %>>{{ $option_label }} </option>
							@endforeach
						</select>
					@endforeach
				</div>
			</div>
		<% }); %>
	</div>

</script>


@stop
