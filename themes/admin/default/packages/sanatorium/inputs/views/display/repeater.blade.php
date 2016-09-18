{{-- Repeater --}}
@if ( is_array($entity->{$attribute->slug}) )
    @if ( !empty($entity->{$attribute->slug}) )
        @if ( is_array($entity->{$attribute->slug}) )
            @foreach( $entity->{$attribute->slug} as $value )
                {!! textUrls2Links($value) !!}
            @endforeach
        @endif
    @endif
@endif
