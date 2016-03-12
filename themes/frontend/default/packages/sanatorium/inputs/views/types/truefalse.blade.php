<div class="checkbox {{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}">

		<input type="hidden" name="{{ $attribute->slug }}" value="0">

		<input type="checkbox" id="{{ $attribute->slug }}" name="{{ $attribute->slug }}[]" value="1" {{ $entity->{$attribute->slug} == 1 ? 'checked' : '' }}>
		{{{ $attribute->name }}}

	</label>

</div>
