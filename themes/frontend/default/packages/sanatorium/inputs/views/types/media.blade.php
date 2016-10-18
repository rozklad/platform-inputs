<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}" id="form-group-{{ $attribute->slug }}">

    <label for="{{ $attribute->slug }}">

        @if ($attribute->description)
            <i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
        @endif

        {{{ transattr($attribute->slug, $attribute->name) }}}

    </label>

    <input type="file" name="{{ $attribute->slug }}">

    <div id="preview-container-{{ $attribute->slug }}">
        @display($entity, $attribute->slug)
    </div>

</div>