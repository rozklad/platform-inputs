<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

    <label for="{{ $attribute->slug }}" class="control-label">
        {{{ $attribute->name }}}
    </label>

    <input class="form-control redactor" name="{{ $attribute->slug }}" id="{{ $attribute->slug }}">

    <span class="help-block">{{{ Alert::onForm($attribute->slug) }}}</span>

</div>