{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}
{{ Asset::queue('media-manager', 'sanatorium/inputs::media/media.js', 'jquery') }}
{{ Asset::queue('media-manager', 'sanatorium/inputs::media/media.scss') }}
{{ Asset::queue('clipboard', 'sanatorium/inputs::clipboard/clipboard.js') }}
{{ Asset::queue('moment', 'moment/js/moment.js', 'jquery') }}
{{ Asset::queue('fileapi', 'platform/media::js/FileAPI/FileAPI.min.js') }}
{{ Asset::queue('fileapi-exif', 'platform/media::js/FileAPI/FileAPI.exif.js') }}

<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}" id="form-group-{{ $attribute->slug }}">

    <label for="{{ $attribute->slug }}">

        @if ($attribute->description)
            <i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
        @endif

        {{{ $attribute->name }}}

    </label>

    @display($entity, $attribute->slug, $mode)

    @if ( $mode == 'multiple' )
        @if ( is_array($entity->{$attribute->slug}) )
            @foreach( $entity->{$attribute->slug} as $value )

                {{-- Sometimes the array consists of single item children [0 => X] --}}
                @if ( is_array($value) && count($value) == 1 )
                    <?php $value = $value[0]; ?>
                @endif

                <input type="hidden" name="{{ $attribute->slug }}[]" value="{{ $value }}">
            @endforeach
        @endif
        <input type="hidden" name="{{ $attribute->slug }}[]">
    @else
        <input type="hidden" name="{{ $attribute->slug }}" value="{{ $entity->{$attribute->slug} }}">
    @endif

    <br>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#media-manager-{{ $attribute->slug }}">
        {{ trans('sanatorium/inputs::types.media.select') }}
    </button>

    <span class="help-block"></span>

</div>


<!-- Modal -->
<div class="modal modal-xlg fade media-manager" id="media-manager-{{ $attribute->slug }}" tabindex="-1" role="dialog" aria-labelledby="media-manager-{{ $attribute->slug }}-label" data-mode="{{ $mode }}" data-input-name="{{ ( $mode == 'multiple' ? $attribute->slug . '[]' : $attribute->slug ) }}" data-form-group="#form-group-{{ $attribute->slug }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-modal-secondary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="media-manager-{{ $attribute->slug }}-label">
                    {{ trans('sanatorium/inputs::types.media.select') }}
                </h4>
                <ul class="nav nav-tabs original" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#media-manager-{{ $attribute->slug }}-current" aria-controls="media-manager-{{ $attribute->slug }}-current" role="tab" data-toggle="tab">
                            {{ trans('sanatorium/inputs::types.media.media_entity') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#media-manager-{{ $attribute->slug }}-library" aria-controls="media-manager-{{ $attribute->slug }}-library" role="tab" data-toggle="tab">
                            {{ trans('sanatorium/inputs::types.media.media_library') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="modal-body">
                <div class="media-manager-browser scrollable-y">
                    <div class="media-manager-toolbar">
                        <div class="media-manager-toolbar-block">
                            <select class="form-control" data-media-sort>
                                <option value="created_at">{{ trans('sanatorium/inputs::types.media.sort.created_at') }}</option>
                                <option value="name">{{ trans('sanatorium/inputs::types.media.sort.name') }}</option>
                                <option value="size">{{ trans('sanatorium/inputs::types.media.sort.size') }}</option>
                            </select>
                        </div>
                        <div class="media-manager-toolbar-block">
                            <select class="form-control" data-media-filter>
                                <option value="images">{{ trans('sanatorium/inputs::types.media.filter.images') }}</option>
                                <option value="documents">{{ trans('sanatorium/inputs::types.media.filter.documents') }}</option>
                                <option value="audio">{{ trans('sanatorium/inputs::types.media.filter.audio') }}</option>
                                <option value="video">{{ trans('sanatorium/inputs::types.media.filter.video') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="media-manager-{{ $attribute->slug }}-current">

                            <!-- tady by měl být původně uploader -->
                            <i class="fa fa-upload fa-5x"></i>
                            <h4>{{ trans('sanatorium/inputs::types.media.upload.select') }}</h4>
                            <p class="lead">{{ trans('sanatorium/inputs::types.media.upload.allowed') }}</p>
                            <p class="small">
                                <i>
                                    {{ implode(', ', config('cartalyst.filesystem.allowed_mimes')) }}
                                </i>
                            </p>

                        </div>
                        <div role="tabpanel" class="tab-pane" id="media-manager-{{ $attribute->slug }}-library">
                            <div data-media-load="{{ route('sanatorium.inputs.media.all') }}" data-entity-id="{{ $entity->id }}" data-entity-type="{{ get_class($entity) }}">

                                <!-- tady by měl být původně uploader -->

                            </div>
                        </div>
                    </div>
                </div>
                <div class="media-manager-sidebar hidden-xs bg-modal-secondary">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    {{ trans('action.close') }}
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    {{ trans('action.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/x-template-lodash" id="media-preview-template">

    <% if ( media.length > 0 ) { %>

    <ol class="media-manager-list">

        <% _.each( media, function( medium, key ){ %>

        <li class="media-manager-preview"
            <%_.each( medium, function ( value, key ){ %>
                data-<%= key %>="<%= value %>"
            <% }); %>
            >

            <div class="media-manager-preview-inner">

                <div class="media-manager-preview-thumbnail <%= (media.is_image ? 'not-image' : (media.width > media.height ? 'landscape' : 'portrait' ) ) %>">

                    <div class="media-manager-preview-centered">

                        <% if ( medium.is_image ) { %>
                            <img src="<%= medium.thumbnail %>" class="media-manager-preview-image">
                        <% } else if ( (medium.mime == 'audio/ogg') || (medium.mime == 'video/mp4') || (medium.mime == 'video/ogg') ) { %>
                            <i class="fa fa-file-movie-o"></i>
                        <% } else if (medium.mime == 'application/zip') { %>
                            <i class="fa fa-file-zip-o"></i>
                        <% } else if (medium.mime == 'application/pdf') { %>
                            <i class="fa fa-file-pdf-o"></i>
                        <% } else { %>
                            <i class="fa fa-file-o"></i>
                        <% } %>

                    </div>

                </div>

            </div>

        </li>

        <% }); %>

    </ol>

    <% } %>

</script>

<script type="text/x-template-lodash" id="media-sidebar-template">

    <h3>{{ trans('sanatorium/inputs::types.media.media_details') }}</h3>

    <div class="media-manager-attachment">
        <% if ( is_image ) { %>
            <img src="<%= thumbnail %>">
        <% } else if ( (mime == 'audio/ogg') || (mime == 'video/mp4') || (mime == 'video/ogg') ) { %>
            <i class="fa fa-file-movie-o"></i>
        <% } else if (mime == 'application/zip') { %>
            <i class="fa fa-file-zip-o"></i>
        <% } else if (mime == 'application/pdf') { %>
            <i class="fa fa-file-pdf-o"></i>
        <% } else { %>
            <i class="fa fa-file-o"></i>
        <% } %>
    </div>

    <div class="media-manager-details">

        <div class="filename"><%= name %></div>

        <div class="created_at">
            <%= moment(created_at).format('MMM DD, YYYY') %>
        </div>

        <div class="filesize">
            <%= humanFileSize(size, true) %>
        </div>

        <div class="dimensions">
            <% if ( is_image ) { %>
                <%= width %> &times; <%= height %>
            <% } %>
        </div>

        <div class="mime">
            <%= mime %>
        </div>

        <div class="accessibility">
        <% if (private == 1) { %>
            <i class="fa fa-lock"></i>
        <% } else { %>
            <i class="fa fa-unlock"></i>
        <% } %>
        </div>

        <div class="tags">
        <% _.each(tags, function(tag) { %>
            <span class="label label-default"><%= tag.name %></span>
        <% }); %>
        </div>

    </div>

    <div class="tool-sink">
        <div class="btn-group btn-group-justified" role="group">
            <a href="<%= view_uri %>" class="btn btn-default">
                <i class="fa fa-share-alt"></i>
            </a>
            <a href="<%= download_uri %>" class="btn btn-default">
                <i class="fa fa-download"></i>
            </a>
            <a href="<%= email_uri %>" class="btn btn-default">
                <i class="fa fa-envelope"></i>
            </a>
            <a href="<%= edit_uri %>" class="btn btn-default">
                <i class="fa fa-pencil"></i>
            </a>
            <a href="<%= delete_uri %>" class="btn btn-default">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </div>

    <div class="tool-sink">
        <!-- Public url -->
        <div class="input-group">
            <input type="text" class="form-control" readonly value="<%= public_url %>" id="media-manager-public-url-<%= id %>" onClick="this.select();">

            <!-- Copy to clipboard -->
            <div class="input-group-btn">
                <button class="btn btn-default" type="button" data-clipboard-target="#media-manager-public-url-<%= id %>" title="{{ trans('sanatorium/inputs::types.media.actions.copy_to_clipboard') }} ">
                    <i class="fa fa-clipboard" aria-hidden="true"></i>
                </button>
            </div>

        </div>
    </div>


</script>

