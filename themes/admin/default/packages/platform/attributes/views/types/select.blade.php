<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}">

		@if ($attribute->description)
		<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
		@endif

		{{{ transattr($attribute->slug, $attribute->name) }}}

	</label>

	<select name="{{ $attribute->slug }}" id="{{ $attribute->slug }}" class="form-control">
		@foreach ($attribute->options as $key => $value)
		<option value="{{ $key }}" {{ request()->old($attribute->slug, $entity->exists ? $entity->{$attribute->slug} : null) == $key ? ' selected="selected"' : null }}>
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
