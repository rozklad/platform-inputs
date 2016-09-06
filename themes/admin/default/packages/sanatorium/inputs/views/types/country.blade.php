<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

    <label for="{{ $attribute->slug }}" class="control-label">
        {{{ transattr($attribute->slug, $attribute->name) }}}
    </label>

    <select class="form-control" name="{{ $attribute->slug }}" id="{{ $attribute->slug }}">
        @foreach( $countries as $country )
            <option value="{{ $country['code'] }}" {{ $country['code'] == $entity->{$attribute->slug} ? 'selected' : '' }}>{{ $country['name'] }}</option>
        @endforeach
    </select>

    <span class="help-block">{{{ Alert::onForm($attribute->slug) }}}</span>

</div>