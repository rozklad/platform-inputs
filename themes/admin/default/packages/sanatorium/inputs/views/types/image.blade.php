<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

    <label for="{{ $attribute->slug }}">

        @if ($attribute->description)
            <i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
        @endif

        {{{ $attribute->name }}}

    </label>

    <br>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#media-manager-{{ $attribute->slug }}">
        {{ trans('sanatorium/inputs::types.image.select') }}
    </button>

    <span class="help-block"></span>

</div>


<!-- Modal -->
<div class="modal modal-xlg fade" id="media-manager-{{ $attribute->slug }}" tabindex="-1" role="dialog" aria-labelledby="media-manager-{{ $attribute->slug }}-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-modal-secondary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="media-manager-{{ $attribute->slug }}-label">
                    {{ trans('sanatorium/inputs::types.image.select') }}
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-10">

                    </div>
                    <div class="col-sm-2 bg-modal-secondary">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>