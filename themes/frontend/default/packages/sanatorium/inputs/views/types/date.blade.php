{{-- Queue assets --}}
{{ Asset::queue('bootstrap-datepicker', 'sanatorium/inputs::bootstrap/bootstrap-datepicker.css') }}
{{ Asset::queue('bootstrap-datepicker', 'sanatorium/inputs::bootstrap/bootstrap-datepicker.js', ['jquery']) }}

{{-- Inline scripts --}}
@section('scripts')
    @parent
    <script type="text/javascript">
        $(function(){
            $('#{{ $attribute->slug }}').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: {{ (int)config('date.week.dow') }}
            });
        });
    </script>
@stop


<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

    <label for="{{ $attribute->slug }}" class="control-label">
        {{{ transattr($attribute->slug, $attribute->name) }}}
    </label>

    <input class="datepicker form-control" name="{{ $attribute->slug }}" id="{{ $attribute->slug }}" value="{{ $entity->{$attribute->slug} }}">

    <span class="help-block">{{{ Alert::onForm($attribute->slug) }}}</span>

</div>
