<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}">

		@if ($attribute->description)
		<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
		@endif

		{{{ transattr($attribute->slug, $attribute->name) }}}

	</label>

	@foreach ($attribute->options as $key => $value)
	<div class="radio">
		<label class="radio">
			<input type="radio" name="{{ $attribute->slug }}" value="{{ $key }}" {{ request()->old($attribute->slug, $entity->exists ? $entity->{$attribute->slug} : null) == $key ? ' checked="true"' : null }}> {{ $value }}
		</label>
	</div>
	@endforeach

	<span class="help-block"></span>

</div>
