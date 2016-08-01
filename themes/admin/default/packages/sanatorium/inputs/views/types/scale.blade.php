<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

    <label for="{{ $attribute->slug }}" class="control-label">
        {{{ $attribute->name }}}
    </label>

    <div class="row">
        <div class="col-xs-11">
            <input type="range"
                   min="0"
                   max="100"
                   value="{{ $entity->{$attribute->slug} }}"
                   name="{{ $attribute->slug }}"
                   id="{{ $attribute->slug }}"
                   step="1" oninput="outputUpdate{{ str_replace('-', '', $attribute->slug) }}(value)">

        </div>
        <div class="col-xs-1 text-center">
            <output for="{{ $attribute->slug }}" id="output{{ $attribute->slug }}">{{ $entity->{$attribute->slug} or '50' }}</output>
        </div>
    </div>

    <span class="help-block">{{{ Alert::onForm($attribute->slug) }}}</span>

</div>

<script type="text/javascript">
    function outputUpdate{{ str_replace('-', '', $attribute->slug) }}(vol) {
        document.querySelector('#output{{ $attribute->slug }}').value = vol;
    }
</script>

<style type="text/css">
    output {
        padding-top: 0;
    }
</style>