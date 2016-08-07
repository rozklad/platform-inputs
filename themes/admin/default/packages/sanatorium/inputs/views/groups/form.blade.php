@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{{ trans("action.{$mode}") }}} {{ trans('sanatorium/inputs::groups/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}
{{ Asset::queue('slugify', 'platform/js/slugify.js', 'jquery') }}
{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}
{{ Asset::queue('interact', 'sanatorium/inputs::interact/interact.js') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
	<script type="text/javascript">

		window.settings = {!! ($group->settings ? $group->settings : '[]') !!};

		function buildForm(settings) {
			var html = _.template( $('#form').html() )( {settings: settings} );
			return html;
		}

		function buildFormGlobal() {
			var html = buildForm(window.settings);
			$canvas = $('#form-builder').find('.canvas');
			$canvas.html(html);

			$('[name="settings"]').val(JSON.stringify(window.settings));

			activateContextButtons($('#form-builder'));

			activateDropzone();

		}

		function activateContextButtons($builder) {
			$builder.find('.delete-row').click(function(event){

				var index = $(this).parents('[data-row]:first').index();

				window.settings.splice(index, 1);

				buildFormGlobal();

			});
		}

		function activateDropzone() {

			interact('.draggable').draggable({
				onmove: dragMoveListener
			});

			interact('.dropzone').dropzone({
				//accept: '.droppable',
				overlap: 'pointer',
				ondropactivate: function (event) {
					// add active dropzone feedback
					event.target.classList.add('drop-active');
				},
				ondragenter: function (event) {
					var draggableElement = event.relatedTarget,
						dropzoneElement = event.target;

					// feedback the possibility of a drop
					dropzoneElement.classList.add('drop-target');
					draggableElement.classList.add('can-drop');
					//draggableElement.textContent = 'Dragged in';
				},
				ondragleave: function (event) {
					// remove the drop feedback style
					event.target.classList.remove('drop-target');
					event.relatedTarget.classList.remove('can-drop');
					//event.relatedTarget.textContent = 'Dragged out';
				},
				ondrop: function (event) {
					var attribute = $(event.relatedTarget).data('attribute'),
						name = $(event.relatedTarget).data('name');

					var rowindex = $(event.target).parents('[data-row]:first').index(),
						colindex = $(event.target).parents('[data-col]:first').index();

					window.settings[rowindex].fields[colindex] = {
						attribute: attribute,
						name: name
					};

					$(event.relatedTarget).remove();

					buildFormGlobal();

					//event.relatedTarget.textContent = 'Dropped';
				},
				ondropdeactivate: function (event) {
					// remove active dropzone feedback
					event.target.classList.remove('drop-active');
					event.target.classList.remove('drop-target');
				}
			});

		}

		function dragMoveListener (event) {
			var target = event.target,
				// keep the dragged position in the data-x/data-y attributes
				x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
				y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

			// translate the element
			target.style.webkitTransform =
					target.style.transform =
							'translate(' + x + 'px, ' + y + 'px)';

			// update the posiion attributes
			target.setAttribute('data-x', x);
			target.setAttribute('data-y', y);
		}

		// this is used later in the resizing and gesture demos
		window.dragMoveListener = dragMoveListener;

		$(function(){

			$('#form-builder').each(function(){
				var $builder = $(this);

				buildFormGlobal();

				$builder.find('.add-row').click(function(event){

					var cols = prompt("Number of columns", "1");

					if ( parseInt(cols, 10) == 0 ) {
						cols = 1;
					}

					var row = {fields: []};
					for ( var i = 0; i < cols; i++ ) {
						row.fields.push({});
					}

					window.settings.push(row);

					buildFormGlobal();

				});

			});
		});
	</script>
@stop

{{-- Inline styles --}}
@section('styles')
@parent
	<style type="text/css">
		.form-builder .canvas {
			border: 2px dashed #ccc;
			padding: 20px;
			position: relative;
		}
		.form-builder .canvas .row {
			position: relative;
			padding-top: 10px;
			padding-bottom: 10px;
		}
		.form-builder .canvas .row .panel {
			margin-bottom: 0;
		}
		.form-builder .canvas-wrapper {
			position: relative;
		}
		.form-builder .canvas-wrapper .add-row, .form-builder .canvas-wrapper .delete-row {
			width: 32px;
			height: 32px;
			display: block;
			border-radius: 50%;
			color: #fff;
			box-shadow: 0 0 10px rgba(0,0,0,0.4);
			line-height: 32px;
			padding: 0;
			outline: 0;
			position: absolute;
		}
		.form-builder .canvas-wrapper .add-row {
			left: 50%;
			margin-left: -16px;
			margin-bottom: -16px;
			bottom: 0;
		}
		.form-builder .canvas-wrapper .delete-row {
			right: 0;
			margin-right: -16px;
			margin-top: -16px;
			top: 50%;
		}
		.form-builder .canvas-wrapper .add-row:hover {
			box-shadow: 0 0 10px rgba(0,0,0,0.8);
		}

		/* Dropzone */
		.dropzone {
			background-color: #ccc;
			border: dashed 4px transparent;
			min-height: 40px;
			transition: background-color 0.3s;
		}

		.drop-active {
			border-color: #aaa;
		}

		.drop-target {
			background-color: #29e;
			border-color: #fff;
			border-style: solid;
		}

		.drag-drop {
			display: block;

			-webkit-transform: translate(0px, 0px);
			transform: translate(0px, 0px);

			transition: background-color 0.3s;
		}

		.drag-drop.can-drop {
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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.sanatorium.inputs.groups.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
						</a>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $group->exists ? $group->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($group->exists)
							<li>
								<a href="{{ route('admin.sanatorium.inputs.groups.delete', $group->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
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
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('sanatorium/inputs::groups/common.tabs.general') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<legend>{{ trans('sanatorium/inputs::groups/common.tabs.general') }}</legend>

							<div class="row">

								<div class="col-sm-6">

									<div class="form-group{{ Alert::onForm('name', ' has-error') }}">

										<label for="name" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/inputs::groups/model.general.name_help') }}}"></i>
											{{{ trans('sanatorium/inputs::groups/model.general.name') }}}
										</label>

										<input type="text" class="form-control" name="name" id="name" placeholder="{{{ trans('sanatorium/inputs::groups/model.general.name') }}}" value="{{{ input()->old('name', $group->name) }}}" data-slugify="#slug" required data-parsley-trigger="change">

										<span class="help-block">{{{ Alert::onForm('name') }}}</span>

									</div>

								</div>

								<div class="col-sm-6">

									<div class="form-group{{ Alert::onForm('slug', ' has-error') }}">

										<label for="slug" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/inputs::groups/model.general.slug_help') }}}"></i>
											{{{ trans('sanatorium/inputs::groups/model.general.slug') }}}
										</label>

										<input type="text" class="form-control" name="slug" id="slug" placeholder="{{{ trans('sanatorium/inputs::groups/model.general.slug') }}}" value="{{{ input()->old('slug', $group->slug) }}}" required data-parsley-trigger="change">

										<span class="help-block">{{{ Alert::onForm('slug') }}}</span>

									</div>

								</div>

							</div>

							<div class="row hidden">

								<div class="col-sm-6">

									<div class="form-group{{ Alert::onForm('weight', ' has-error') }}">

										<label for="weight" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/inputs::groups/model.general.weight_help') }}}"></i>
											{{{ trans('sanatorium/inputs::groups/model.general.weight') }}}
										</label>

										<input type="text" class="form-control" name="weight" id="weight" placeholder="{{{ trans('sanatorium/inputs::groups/model.general.weight') }}}" value="{{{ input()->old('weight', $group->weight) }}}">

										<span class="help-block">{{{ Alert::onForm('weight') }}}</span>

									</div>

								</div>

							</div>

						</fieldset>

						<fieldset>

							<legend>{{ trans('sanatorium/inputs::groups/common.tabs.builder') }}</legend>

							<input type="hidden" name="settings" value="{!! ($group->settings ? $group->settings : '[]') !!}">

							<div id="form-builder" class="form-builder">

								<div class="row">
									<div class="col-sm-10 canvas-wrapper">
										<div class="canvas">

										</div>
										<button type="button" class="add-row btn btn-success">
											<i class="fa fa-plus"></i>
										</button>
									</div>
									<div class="col-sm-2">
										<div class="picker">
											@if ( $group->attributes()->count() > 0 )
												@foreach( $group->attributes()->get() as $attribute )
													<div class="panel panel-default droppable draggable drag-drop" data-name="{{ $attribute->name }}" data-attribute="{{ $attribute->slug }}">
														<div class="panel-heading">
															{{ $attribute->name }}
															<small class="text-muted">{{ $attribute->slug }}</small>
														</div>
													</div>
												@endforeach
											@else
												<p class="alert alert-info">
													{{ trans('sanatorium/inputs::groups/message.no_inputs') }}
												</p>
											@endif
										</div>
									</div>
								</div>

							</div>

						</fieldset>

					</div>

				</div>

			</div>

		</div>

	</form>

</section>

@include('sanatorium/inputs::groups/form/row')

@stop
