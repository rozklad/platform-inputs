<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}">

		@if ($attribute->description)
		<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
		@endif

		{{{ transattr($attribute->slug, $attribute->name) }}}

	</label>

	<textarea class="form-control" name="{{ $attribute->slug }}" id="{{ $attribute->slug }}">{{{ request()->old($attribute->slug, ! empty($entity) ? $entity->{$attribute->slug} : null) }}}</textarea>

	<span class="help-block"></span>

</div>
