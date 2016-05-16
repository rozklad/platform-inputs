
@foreach( $media as $medium )
    @if ( isset($medium) && $medium->is_image )
        <div class="media-image-preview">
            <img src="{{ $medium->thumbnail }}" alt="{{ $medium->name }}">
        </div>
    @else
        {{-- empty media --}}
    @endif
@endforeach