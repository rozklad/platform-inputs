{{ Asset::queue('redactor', 'redactor/js/redactor.min.js', 'jquery') }}
{{ Asset::queue('redactor', 'redactor/css/redactor.css', 'styles') }}


<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}" class="control-label">
		{{{ transattr($attribute->slug, $attribute->name) }}}
	</label>

	<textarea class="form-control redactor" name="{{ $attribute->slug }}" id="{{ $attribute->slug }}" data-parsley="false">{!! input()->old($attribute->slug, $entity->{$attribute->slug}) !!}</textarea>

	<span class="help-block">{{{ Alert::onForm($attribute->slug) }}}</span>

</div>