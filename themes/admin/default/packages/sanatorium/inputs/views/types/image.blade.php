{{ Asset::queue('media-manager', 'sanatorium/inputs::media/media.js', 'jquery') }}

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
<div class="modal modal-xlg fade media-manager" id="media-manager-{{ $attribute->slug }}" tabindex="-1" role="dialog" aria-labelledby="media-manager-{{ $attribute->slug }}-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-modal-secondary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="media-manager-{{ $attribute->slug }}-label">
                    {{ trans('sanatorium/inputs::types.image.select') }}
                </h4>
                <ul class="nav nav-tabs original" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#media-manager-{{ $attribute->slug }}-current" aria-controls="media-manager-{{ $attribute->slug }}-current" role="tab" data-toggle="tab">
                            {{ trans('sanatorium/inputs::types.image.media_entity') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#media-manager-{{ $attribute->slug }}-library" aria-controls="media-manager-{{ $attribute->slug }}-library" role="tab" data-toggle="tab">
                            {{ trans('sanatorium/inputs::types.image.media_library') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-10">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="media-manager-{{ $attribute->slug }}-current">
                                <div data-media-load="{{ route('sanatorium.inputs.media.entity') }}" data-entity-id="{{ $entity->id }}" data-entity-type="{{ get_class($entity) }}">
                                    Current
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="media-manager-{{ $attribute->slug }}-library">
                                <div data-media-load="{{ route('sanatorium.inputs.media.all') }}">
                                    All
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2 hidden-xs bg-modal-secondary">

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