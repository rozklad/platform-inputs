<?php $rand = rand(); ?>
<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

    <label for="{{ $attribute->slug . $rand }}" class="control-label">
        {{{ $attribute->name }}}
    </label>

    <div class="row">
        <div class="col-xs-11">
            <input type="range"
                   min="0"
                   max="100"
                   value="{{ $entity->{$attribute->slug} }}"
                   name="{{ $attribute->slug }}"
                   id="{{ $attribute->slug . $rand }}"
                   step="1" oninput="outputUpdate{{ str_replace('-', '', $attribute->slug . $rand) }}(value)">

        </div>
        <div class="col-xs-1 text-center">
            <output for="{{ $attribute->slug . $rand }}" id="output{{ $attribute->slug . $rand }}">{{ $entity->{$attribute->slug} or '50' }}</output>
        </div>
    </div>

    <span class="help-block">{{{ Alert::onForm($attribute->slug) }}}</span>

</div>

<script type="text/javascript">
    function outputUpdate{{ str_replace('-', '', $attribute->slug . $rand) }}(vol) {
        document.querySelector('#output{{ $attribute->slug . $rand }}').value = vol;
        document.querySelector('#{{ $attribute->slug . $rand }}').value = vol;
    }
</script>

<style type="text/css">
    output {
        padding-top: 0;
    }
</style>