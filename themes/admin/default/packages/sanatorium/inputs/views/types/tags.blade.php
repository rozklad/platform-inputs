@section('scripts')
    @parent
    <script type="text/javascript">
        $(function(){
            var $this = $('#{{ $attribute->slug }}');

            $this.selectize({
                create: typeof $this.data('create') !== 'undefined' ? function(input) {
                    return {
                        value: input,
                        text: input
                    }
                } : false,
                sortField: 'text'
            });
        });
    </script>
@stop
<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

    <label for="{{ $attribute->slug }}">

        @if ($attribute->description)
            <i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
        @endif

        {{{ transattr($attribute->slug, $attribute->name) }}}

    </label>

    <input type="hidden" name="{{ $attribute->slug }}" value="">

    <select multiple="multiple" name="{{ $attribute->slug }}[]" id="{{ $attribute->slug }}" class="form-control no-selectize" {{ Sentinel::hasAnyAccess(['superuser', 'inputs.tags.create']) ? 'data-create' : '' }}>
        <option></option>
        @foreach ($attribute->values()->get() as $key => $value)
            @foreach( json_decode($value->value, true) as $item )
                <option value="{{ $item }}"{{ in_array($item, $entity->exists ? (is_array($entity->{$attribute->slug}) ? $entity->{$attribute->slug} : array()) : array()) ? ' selected="selected"' : null }}>{{ $item }}</option>
            @endforeach
        @endforeach
    </select>

    <span class="help-block"></span>

</div>