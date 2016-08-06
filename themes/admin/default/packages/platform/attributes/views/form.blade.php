@extends('layouts/default')

{{-- Page title --}}
@section('title')
    @parent
    {{{ trans("action.{$mode}") }}} {{{ trans('platform/attributes::common.title') }}}
@stop

{{-- Queue assets --}}
{{ Asset::queue('attributes', 'platform/attributes::css/form.scss') }}
{{ Asset::queue('selectize', 'selectize/css/selectize.bootstrap3.css', 'styles') }}

{{ Asset::queue('slugify', 'platform/js/slugify.js', 'jquery') }}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}
{{ Asset::queue('selectize', 'selectize/js/selectize.js', 'jquery') }}
{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}
{{ Asset::queue('sortable', 'platform/attributes::js/jquery.sortable.js', 'jquery') }}
{{ Asset::queue('form', 'platform/attributes::js/form.js', [ 'platform', 'sortable', 'selectize', 'underscore', ]) }}

{{-- Inline styles --}}
@section('styles')
    @parent
@stop

{{-- Inline scripts --}}
@section('scripts')
    @parent
    <script type="text/javascript">
        Extension.Form.setOptions({!! json_encode($options) !!});

        $(function(){
           $('select[name="type"]').change(function(event){
                var value = $(this).val();
               if ( value == 'relation' ) {
                   $('.visible-relation').removeClass('hidden');
               } else {
                   $('.visible-relation').addClass('hidden');
               }
           });
        });
    </script>
@stop

{{-- Page content --}}
@section('page')
    <section class="panel panel-default panel-tabs">

        {{-- Form --}}
        <form id="attributes-form" action="{{ request()->fullUrl() }}" method="post" accept-char="UTF-8" autocomplete="off" data-parsley-validate>

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

                            <a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.attributes.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
                                <i class="fa fa-reply"></i> <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
                            </a>

                            <span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $attribute->name}}}</small></span>
                        </div>

                        {{-- Form: Actions --}}
                        <div class="collapse navbar-collapse" id="actions">

                            <ul class="nav navbar-nav navbar-right">

                                @if ($attribute->exists)

                                    <li>
                                        <a href="{{ route('admin.attribute.delete', $attribute->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
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
                        <li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{{ trans('platform/attributes::common.tabs.general') }}}</a></li>
                    </ul>

                    <div class="tab-content">

                        {{-- Tab: General --}}
                        <div role="tabpanel" class="tab-pane fade in active" id="general-tab">

                            <div class="row">

                                <div class="col-lg-5">

                                    <fieldset>

                                        <legend>{{{ trans('platform/attributes::model.general.legend') }}}</legend>

                                        <div class="row">

                                            <div class="col-lg-6">

                                                {{-- Name --}}
                                                <div class="form-group{{ Alert::onForm('name', ' has-error') }}">

                                                    <label for="name" class="control-label">
                                                        <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('platform/attributes::model.general.name_help') }}}"></i>
                                                        {{{ trans('platform/attributes::model.general.name') }}}
                                                    </label>

                                                    <input type="text" class="form-control" name="name" id="name" placeholder="{{{ trans('platform/attributes::model.general.name') }}}" value="{{{ request()->old('name', $attribute->name) }}}" data-slugify="#slug" required data-parsley-trigger="change">

                                                    <span class="help-block">{{{ Alert::onForm('name') }}}</span>

                                                </div>

                                            </div>

                                            <div class="col-lg-6">

                                                {{-- Slug --}}
                                                <div class="form-group{{ Alert::onForm('slug', ' has-error') }}">

                                                    <label for="slug" class="control-label">
                                                        <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('platform/attributes::model.general.slug_help') }}}"></i>
                                                        {{{ trans('platform/attributes::model.general.slug') }}}
                                                    </label>

                                                    <input type="text" class="form-control" name="slug" id="slug" placeholder="{{{ trans('platform/attributes::model.general.slug') }}}" value="{{{ request()->old('slug', $attribute->slug) }}}" required data-parsley-trigger="change">

                                                    <span class="help-block">{{{ Alert::onForm('slug') }}}</span>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-lg-6">

                                                {{-- Namespace --}}
                                                <div class="form-group{{ Alert::onForm('namespace', ' has-error') }}">

                                                    <label for="namespace" class="control-label">
                                                        <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('platform/attributes::model.general.namespace_help') }}}"></i>
                                                        {{{ trans('platform/attributes::model.general.namespace') }}}
                                                    </label>

                                                    <select class="form-control" name="namespace" id="namespace">
                                                        <option value="">Select a namespace...</option>
                                                        @foreach ($namespaces as $namespace)
                                                            <option {{ request()->old('namespace', request()->get('namespace', $attribute->namespace)) === $namespace ? ' selected="selected"' : null}} value="{{{ $namespace }}}">{{{ $namespace }}}</option>
                                                        @endforeach
                                                    </select>

                                                    <span class="help-block">{{{ Alert::onForm('namespace') }}}</span>

                                                </div>

                                            </div>

                                            <div class="col-lg-6">

                                                {{-- Status --}}
                                                <div class="form-group{{ Alert::onForm('enabled', ' has-error') }}">

                                                    <label for="enabled" class="control-label">
                                                        <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('platform/attributes::model.general.enabled_help') }}}"></i>
                                                        {{{ trans('platform/attributes::model.general.enabled') }}}
                                                    </label>

                                                    <select class="form-control" name="enabled" id="enabled" required data-parsley-trigger="change">
                                                        <option value="1"{{ input()->old('enabled', $attribute->enabled) == 1 ? ' selected="selected"' : null}}>{{{ trans('common.enabled') }}}</option>
                                                        <option value="0"{{ input()->old('enabled', $attribute->enabled) == 0 ? ' selected="selected"' : null}}>{{{ trans('common.disabled') }}}</option>
                                                    </select>

                                                    <span class="help-block">{{{ Alert::onForm('status') }}}</span>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-12">

                                                {{-- Description --}}
                                                <div class="form-group{{ Alert::onForm('description', ' has-error') }}">

                                                    <label for="description" class="control-label">
                                                        <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('platform/attributes::model.general.description_help') }}}"></i>
                                                        {{{ trans('platform/attributes::model.general.description') }}}
                                                    </label>

                                                    <input type="text" class="form-control" name="description" id="description" placeholder="{{{ trans('platform/attributes::model.general.description') }}}" value="{{{ request()->old('description', $attribute->description) }}}" required data-parsley-trigger="change">

                                                    <span class="help-block">{{{ Alert::onForm('description') }}}</span>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="row visible-relation {{ $attribute->type == 'relation' ? '' : 'hidden' }}">

                                            <div class="col-md-12">

                                                {{-- Relation --}}
                                                <div class="form-group{{ Alert::onForm('relation', ' has-error') }}">

                                                    <label for="description" class="control-label">
                                                        <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('sanatorium/inputs::model.general.relation_help') }}}"></i>
                                                        {{{ trans('sanatorium/inputs::model.general.relation') }}}
                                                    </label>

                                                    <select name="options[relation]" class="form-control">
                                                        <option value="">{{ trans('sanatorium/inputs::model.general.relation_placeholder') }}</option>
                                                        @foreach( app('sanatorium.inputs.relations')->getRelations() as $key => $value )
                                                            <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>

                                                    <span class="help-block">{{{ Alert::onForm('relation') }}}</span>

                                                </div>

                                            </div>

                                        </div>

                                    </fieldset>

                                </div>

                                <div class="col-lg-7">

                                    <fieldset>

                                        <legend>{{{ trans('platform/attributes::model.types.legend') }}}</legend>

                                        <div class="row">

                                            <div class="col-md-12">

                                                {{-- Type --}}
                                                <div class="form-group{{ Alert::onForm('type', ' has-error') }}">

                                                    <label for="type" class="control-label">
                                                        <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('platform/attributes::model.types.type_help') }}}"></i>
                                                        {{{ trans('platform/attributes::model.types.type') }}}
                                                    </label>

                                                    <select class="form-control" name="type" id="type" data-selectize-disabled required data-parsley-trigger="change">
                                                        <option value="">Select a type...</option>
                                                        @foreach ($types as $type)
                                                            <option data-allow-options="{{ $type->allowOptions() ?: 0 }}"{{ request()->old('type', $attribute->type) === $type->getIdentifier() ? ' selected="selected"' : null }} value="{{{ $type->getIdentifier() }}}">{{{ $type->getName() }}}</option>
                                                        @endforeach
                                                    </select>

                                                    <span class="help-block">{{{ Alert::onForm('type') }}}</span>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="hide" data-no-options>

                                            <div class="jumbotron">
                                                <h4 class="text-center">{{{ trans('platform/attributes::message.options_not_allowed') }}}</h4>
                                            </div>

                                        </div>

                                        <div class="hide" data-options>

                                            <div class="form-group{{ Alert::onForm('options', ' has-error') }}">

                                                <span class="help-block">{{{ Alert::onForm('options') }}}</span>

                                                <ol class="options list-group"></ol>

                                            </div>

                                        </div>

                                    </fieldset>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </section>

    <script type="text/template" data-option-template>

        <li>

            <div class="form-inline">

                <div class="form-group">

                    <div class="input-group handle">
                        <div class="input-group-addon" data-option-move><i class="fa fa-arrows"></i></div>

                        <input class="form-control" id="label" name="options[<%= id %>][label]" type="text" value="<%= label %>" data-slugify="#option-<%= id %>" placeholder="{{{ trans('platform/attributes::model.types.option_label') }}}" data-parsley-ui-enabled="false">
			</div>

			<input class="form-control" id="option-<%= id %>" name="options[<%= id %>][value]" type="text" value="<%= value %>" placeholder="{{{ trans('platform/attributes::model.types.option_value') }}}" data-parsley-ui-enabled="false">

			<button class="btn btn-md btn-default" data-option-add><i class="fa fa-plus"></i></button>
			<button class="btn btn-md btn-default" data-option-remove><i class="fa fa-trash-o"></i></button>

		</div>

	</div>

</li>

</script>
@stop
