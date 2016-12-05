<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}" id="form-group-{{ $attribute->slug }}">

    <label for="{{ $attribute->slug }}">

        @if ($attribute->description)
            <i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
        @endif

        {{{ transattr($attribute->slug, $attribute->name) }}}

    </label>


    <div class="hidden">
        <input type="file" name="{{ $attribute->slug }}" id="input-{{ $attribute->slug }}">
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div id="preview-container-{{ $attribute->slug }}">
                @display($entity, $attribute->slug)
            </div>
        </div>
        <div class="col-sm-6">
            <button type="button" class="btn btn-default" for="input-{{ $attribute->slug }}">
                @if ( $entity->{$attribute->slug} )
                    {{ 'Update picture' }}
                @else
                    {{ 'Add picture' }}
                @endif
            </button>
        </div>
    </div>

</div>

@section('scripts')
    @parent
<script type="text/javascript">
    $(function(){
        $('[for="input-{{ $attribute->slug }}"]').click(function(event){
            event.preventDefault();

            var target = $(this).attr('for');

            $('#' + target).trigger('click');
        });

        $('#input-{{ $attribute->slug }}').change(function(){

            $(this).parents('form:first').trigger('submit');
        });
    });
</script>
@stop

@section('styles')
    @parent
    <style type="text/css">
        .media-image-preview-{{ $attribute->slug }} img {
            width: 100%;
            height: auto;
        }
    </style>
@stop