<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}">

		@if ($attribute->description)
		<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
		@endif

		{{{ transattr($attribute->slug, $attribute->name) }}}

	</label>

	<input type="hidden" name="{{ $attribute->slug }}" value="">

	<select multiple="multiple" name="{{ $attribute->slug }}[]" id="{{ $attribute->slug }}" class="form-control">
		@foreach ($attribute->options as $key => $value)
		<option value="{{ $key }}"{{ in_array($key, $entity->exists ? (is_array($entity->{$attribute->slug}) ? $entity->{$attribute->slug} : array()) : array()) ? ' selected="selected"' : null }}>
			<?php $label = transattr($attribute->slug, $value, null, 'options', $key); ?>
			@if ( $label )
				{{{ $label }}}
			@else
				{{{ $value }}}
			@endif
		</option>
		@endforeach
	</select>

	<span class="help-block"></span>

</div>
