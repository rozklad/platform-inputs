
@if ( !empty($media) )
    @foreach( $media as $medium )
        @if ( isset($medium) && $medium->is_image )
            <div class="sanatorium-inputs-widget-display media-image-preview">
                <img src="{{ $medium->thumbnail }}" alt="{{ $medium->name }}">
            </div>
        @else
            <div class="sanatorium-inputs-widget-display media-image-preview">
                @if ( $medium->mime == 'audio/ogg' || $medium->mime == 'video/mp4' || $medium->mime == 'video/ogg' )
                    <i class="fa fa-file-movie-o"></i>
                @elseif ( $medium->mime == 'application/zip' )
                    <i class="fa fa-file-pdf-o"></i>
                @elseif ( $medium->mime == 'application/pdf' )
                    <i class="fa fa-file-pdf-o"></i>
                @else
                    <i class="fa fa-file-o"></i>
                @endif
            </div>
        @endif
    @endforeach
@endif