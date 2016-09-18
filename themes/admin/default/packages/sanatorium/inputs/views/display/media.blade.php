{{-- Media --}}
@if ( $type == 'media' || $type == 'image' || $type == 'gallery' || $type == 'file' )

    @if ( !empty($media) )
        @foreach( $media as $medium )
            @if ( is_object($medium) )
                @if ($action == 'edit')
                    <div class="sanatorium-inputs-widget-display media-image-preview media-image-preview-{{ $slug }}">
                        @if ( isset($medium) && $medium->is_image || $medium->mime == 'image/svg+xml' )
                            @if ( $medium->is_image )
                                <img src="{{ thumbnail_url($medium, 300) }}" alt="{{ $medium->name }}">
                            @elseif ( $medium->mime == 'image/svg+xml' )
                                <img src="{{ thumbnail_url($medium, 300) }}" alt="{{ $medium->name }}">
                            @endif
                        @else
                            @if ( $medium->mime == 'audio/ogg' || $medium->mime == 'video/mp4' || $medium->mime == 'video/ogg' )
                                <i class="fa fa-file-movie-o"></i>
                            @elseif ( $medium->mime == 'application/zip' )
                                <i class="fa fa-file-pdf-o"></i>
                            @elseif ( $medium->mime == 'application/pdf' )
                                <i class="fa fa-file-pdf-o"></i>
                            @else
                                <i class="fa fa-file-o"></i>
                            @endif
                        @endif

                        <a href="#" class="sanatorium-inputs-widget-display-btn edit" data-toggle="modal" data-target="#media-manager-{{ $slug }}">
                            <i class="fa fa-pencil"></i>
                        </a>

                        <a href="#" class="sanatorium-inputs-widget-display-btn delete"  data-external-control="{{ $slug }}" data-external-type="delete">
                            <i class="fa fa-trash"></i>
                        </a>

                    </div>
                @else
                    <p class="paragraph-file">
                        <?php
                        // Icon for medium
                        switch ( true ) {
                            case $medium->is_image:
                                $icon = 'fa fa-file-image-o';
                                break;
                            case ($medium->mime == 'audio/ogg' || $medium->mime == 'video/mp4' || $medium->mime == 'video/ogg'):
                                $icon = 'fa fa-file-movie-o';
                                break;
                            case ($medium->mime == 'application/zip'):
                                $icon = 'fa fa-file-zip-o';
                                break;
                            case ($medium->mime == 'application/pdf'):
                                $icon = 'fa fa-file-pdf-o';
                                break;
                            default:
                                $icon = 'fa fa-file-o';
                                break;
                        }
                        ?>

                        <i class="{{ $icon }}" aria-hidden="true"></i>
                        <a href="{{ storage_url($medium->path) }}" class="file-link">{{ basename($medium->path) }}</a>
                    </p>
                @endif
            @endif
        @endforeach
    @endif

@endif