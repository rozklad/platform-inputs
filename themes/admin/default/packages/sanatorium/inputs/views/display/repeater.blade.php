{{-- Repeater --}}
@if ( is_array($entity->{$attribute->slug}) )
    @if ( !empty($entity->{$attribute->slug}) )
        @if ( is_array($entity->{$attribute->slug}) )
            @foreach( $entity->{$attribute->slug} as $value )
                @if ( is_array($value) )
                    {{-- @todo: inherit options and show different input display partials --}}
                    @foreach( $value as $sub )
                        @if ( is_string($sub) )
                            {!! textUrls2Links($sub) !!}
                        @endif
                    @endforeach

                @elseif ( is_string($value) )
                    {!! textUrls2Links($value) !!}
                @endif
            @endforeach
        @endif
    @endif
@endif
