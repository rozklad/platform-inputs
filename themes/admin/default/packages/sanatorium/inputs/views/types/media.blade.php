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

        {{{ transattr($attribute->slug, $attribute->name) }}}

    </label>

    <div id="preview-container-{{ $attribute->slug }}">
        @display($entity, $attribute->slug, $mode)
    </div>

    @if ( $mode == 'multiple' )
        @if ( is_array($entity->{$attribute->slug}) )
            @foreach( $entity->{$attribute->slug} as $value )

                {{-- Sometimes the array consists of single item children [0 => X] --}}
                @if ( is_array($value) && count($value) == 1 )
                    <?php $value = $value[0]; ?>
                @endif

                <input type="hidden" name="{{ $attribute->slug }}[]" value="{{ $value }}" class="media-manager-input">
            @endforeach
        @endif
    @else
        <input type="hidden" name="{{ $attribute->slug }}" value="{{ $entity->{$attribute->slug} }}" class="media-manager-input">
    @endif

    <br>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-default btn-trigger-{{ $attribute->slug }} {{ $entity->{$attribute->slug} ? 'hidden' : ''}}" data-toggle="modal" data-target="#media-manager-{{ $attribute->slug }}">
        @if ( isset($label) )
            {{ $label }}
        @else
            {{ trans('sanatorium/inputs::types.media.upload.select') }}
        @endif
    </button>


    <span class="help-block"></span>

</div>


<!-- Modal: data attributes contain specific variables for the request -->
<div class="modal modal-xlg fade media-manager" id="media-manager-{{ $attribute->slug }}" tabindex="-1" role="dialog" aria-labelledby="media-manager-{{ $attribute->slug }}-label"
     data-mode="{{ $mode }}" data-input-name="{{ ( $mode == 'multiple' ? $attribute->slug . '[]' : $attribute->slug ) }}"
     data-media-preview-template="media-preview-template-{{ $attribute->slug }}"
     data-form-group="#form-group-{{ $attribute->slug }}"
     data-search="#media-manager-{{ $attribute->slug }}-search"
     data-token="{{ csrf_token() }}"
     data-upload-url="{{ route('sanatorium.inputs.media.upload') }}"
     data-preview=".media-image-preview-{{ $attribute->slug }}"
     data-preview-container="#preview-container-{{ $attribute->slug }}"
     data-preview-template="#preview-template-{{ $attribute->slug }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-modal-secondary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="media-manager-{{ $attribute->slug }}-label">
                    @if ( isset($label) )
                        {{ $label }}
                    @else
                        {{ trans('sanatorium/inputs::types.media.upload.select') }}
                    @endif
                </h4>
                <ul class="nav nav-tabs original" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#media-manager-{{ $attribute->slug }}-current"
                           aria-controls="media-manager-{{ $attribute->slug }}-current"
                           role="tab"
                           data-toggle="tab"
                           data-tab-control-current>
                            {{ trans('sanatorium/inputs::types.media.media_entity') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#media-manager-{{ $attribute->slug }}-library"
                           aria-controls="media-manager-{{ $attribute->slug }}-library"
                           role="tab"
                           data-toggle="tab"
                           data-tab-control-library>
                            {{ trans('sanatorium/inputs::types.media.media_library') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="modal-body">

                <div class="tab-content">

                    {{-- Upload --}}
                    <div role="tabpanel"
                         class="tab-pane tab-pane-middle media-manager-dropzone active"
                         id="media-manager-{{ $attribute->slug }}-current"
                         data-media-dropzone>

                        <div class="tab-pane-middle-inner">

                            <p class="lead">{{ trans('sanatorium/inputs::types.media.upload.drop') }}</p>

                            <p class="upload-or">{{ trans('sanatorium/inputs::types.media.upload.or') }}</p>

                            <button type="button" class="btn btn-default btn-upload" data-toggle="tab" href="#media-manager-{{ $attribute->slug }}-library">
                                {{ trans('sanatorium/inputs::types.media.upload.select') }}
                            </button>

                            <p class="small">
                                {{ trans('sanatorium/inputs::types.media.upload.allowed') }}:
                                <i>
                                    {{ implode(', ', config('cartalyst.filesystem.allowed_mimes')) }}
                                </i>
                            </p>
                        </div>

                    </div>

                    {{-- Library --}}
                    <div role="tabpanel"
                         class="tab-pane"
                         id="media-manager-{{ $attribute->slug }}-library"
                         data-media-dropzone>

                        <div class="media-manager-browser scrollable-y">

                            {{-- Toolbar --}}
                            <div class="media-manager-toolbar">
                                <div class="media-manager-toolbar-block">
                                    <select class="form-control" data-media-sort>
                                        <option value="created_at:desc">{{ trans('sanatorium/inputs::types.media.sort.newest') }}</option>
                                        <option value="created_at">{{ trans('sanatorium/inputs::types.media.sort.oldest') }}</option>
                                        <option value="name">{{ trans('sanatorium/inputs::types.media.sort.alphabetically_asc') }}</option>
                                        <option value="name:desc">{{ trans('sanatorium/inputs::types.media.sort.alphabetically_desc') }}</option>
                                        <option value="size">{{ trans('sanatorium/inputs::types.media.sort.smallest') }}</option>
                                        <option value="size:desc">{{ trans('sanatorium/inputs::types.media.sort.largest') }}</option>
                                    </select>
                                </div>
                                <div class="media-manager-toolbar-block">
                                    <select class="form-control" data-media-filter>
                                        <option value="all">{{ trans('sanatorium/inputs::types.media.filter.all') }}</option>
                                        <option value="images">{{ trans('sanatorium/inputs::types.media.filter.images') }}</option>
                                        <option value="documents">{{ trans('sanatorium/inputs::types.media.filter.documents') }}</option>
                                        <option value="audio">{{ trans('sanatorium/inputs::types.media.filter.audio') }}</option>
                                        <option value="video">{{ trans('sanatorium/inputs::types.media.filter.video') }}</option>
                                    </select>
                                </div>
                                <div class="media-manager-toolbar-block pull-right">
                                    <input type="text" class="form-control" id="media-manager-{{ $attribute->slug }}-search" placeholder="{{ trans('sanatorium/inputs::types.media.search') }}">
                                </div>
                            </div>

                            {{-- Media list --}}
                            <div data-media-load="{{ route('sanatorium.inputs.media.all') }}" data-entity-id="{{ $entity->id }}" data-entity-type="{{ get_class($entity) }}">

                            </div>

                        </div>

                        <div class="media-manager-sidebar hidden-xs bg-modal-secondary">

                        </div>

                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <div class="modal-status pull-left">

                </div>
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

<script id="file-ejs" type="text/ejs">
    <li class="media-manager-preview" id="file-<%=FileAPI.uid(file)%>">
        <div class="media-manager-preview-inner">
            <div class="media-manager-preview-thumbnail">
                <div class="media-manager-preview-centered">

                    <img src="<%=icon[file.type.split('/')[0]]||icon.def%>" width="300" height="300" style="margin: 2px 0 0 3px" class="media-manager-preview-uploader">

                </div>
               <div class="media-manager-file__bar">
                    <div class="media-manager-progress">
                        <div class="media-manager-progress__bar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</script>

<div class="media-manager-uploading-area"></div>

<script type="text/x-template-lodash" id="preview-template-{{ $attribute->slug }}">

    <% if ( media.length > 0 ) { %>

        <% _.each( media, function( medium, key ){ %>

            <div class="sanatorium-inputs-widget-display media-image-preview">

                <% if ( medium.is_image ) { %>
                    <img src="<%= medium.thumbnail %>">
                <% } else if ( medium.mime == 'image/svg+xml' ) { %>
                    <img src="<%= medium.view_uri %>">
                <% } else if ( (medium.mime == 'audio/ogg') || (medium.mime == 'video/mp4') || (medium.mime == 'video/ogg') ) { %>
                    <i class="fa fa-file-movie-o"></i>
                <% } else if (medium.mime == 'application/zip') { %>
                    <i class="fa fa-file-zip-o"></i>
                <% } else if (medium.mime == 'application/pdf') { %>
                    <i class="fa fa-file-pdf-o"></i>
                <% } else { %>
                    <i class="fa fa-file-o"></i>
                <% } %>

                <a href="#" class="sanatorium-inputs-widget-display-btn edit" data-toggle="modal" data-target="#media-manager-{{ $attribute->slug }}">
                    <i class="fa fa-pencil"></i>
                </a>

                <a href="#" class="sanatorium-inputs-widget-display-btn delete"  data-external-control="{{ $attribute->slug }}" data-external-type="delete">
                    <i class="fa fa-trash"></i>
                </a>

            </div>

        <% }); %>

    <% } %>

</script>

<script type="text/x-template-lodash" id="media-preview-template-{{ $attribute->slug }}">

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
                        <% } else if ( medium.mime == 'image/svg+xml' ) { %>
                            <img src="<%= medium.view_uri %>" class="media-manager-preview-image">
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

    <div class="media-manager-detail-block">

        <div class="media-manager-attachment">
            <% if ( is_image ) { %>
                <img src="<%= thumbnail %>">
            <% } else if ( mime == 'image/svg+xml' ) { %>
                <img src="<%= view_uri %>">
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

            <div class="filename">
                <span>
                    <%= name %>
                </span>
            </div>

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

    </div>

    <div class="tool-sink">
        <div class="btn-group btn-group-justified" role="group">
            <a href="<%= view_uri %>" class="btn btn-default" target="_blank">
                <i class="fa fa-share-alt"></i>
            </a>
            <a href="<%= download_uri %>" class="btn btn-default" target="_blank">
                <i class="fa fa-download"></i>
            </a>
            <a href="<%= email_uri %>" class="btn btn-default" target="_blank">
                <i class="fa fa-envelope"></i>
            </a>
            <a href="<%= edit_uri %>" class="btn btn-default" target="_blank">
                <i class="fa fa-pencil"></i>
            </a>
            <a href="<%= delete_uri %>" class="btn btn-default" data-delete>
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


