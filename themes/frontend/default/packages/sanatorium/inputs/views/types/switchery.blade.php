<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}">

		@if ($attribute->description)
		<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
		@endif

		{{{ $attribute->name }}}

	</label>

	<input type="hidden" name="{{ $attribute->slug }}" value="0">

	@foreach ($attribute->options as $key => $value)
	<div class="checkbox">
		<input type="checkbox" data-init-plugin="switchery" id="option-{{ $attribute->slug }}-{{ $key }}" name="{{ $attribute->slug }}[]" value="{{ $key }}"{{ in_array($key, $entity->exists ? (is_array($entity->{$attribute->slug}) ? $entity->{$attribute->slug} : array()) : array()) ? ' checked="checked"' : null }}>
		<label class="checkbox" for="option-{{ $attribute->slug }}-{{ $key }}">
			{{ $value }}
		</label>
	</div>
	@endforeach

	<span class="help-block"></span>

</div>
