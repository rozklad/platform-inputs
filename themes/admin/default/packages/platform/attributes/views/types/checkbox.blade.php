<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}">

		@if ($attribute->description)
		<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
		@endif

		{{{ transattr($attribute->slug, $attribute->name) }}}

	</label>

	<input type="hidden" name="{{ $attribute->slug }}" value="0">

	@foreach ($attribute->options as $key => $value)
	<div class="checkbox">
		<label class="checkbox">
			<input type="checkbox" name="{{ $attribute->slug }}[]" value="{{ $key }}"{{ in_array($key, $entity->exists ? (is_array($entity->{$attribute->slug}) ? $entity->{$attribute->slug} : array()) : array()) ? ' checked="checked"' : null }}> {{ $value }}
		</label>
	</div>
	@endforeach

	<span class="help-block"></span>

</div>
