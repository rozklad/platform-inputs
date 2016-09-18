{{-- Select --}}
@if ( is_array($entity->{$attribute->slug}) )
    @if ( !empty($entity->{$attribute->slug}) )
        @foreach( $entity->{$attribute->slug} as $option_key )
            @if ( isset($attribute->options[$option_key]) )
                {!! textUrls2Links($attribute->options[$option_key]) !!}
            @endif
        @endforeach
    @endif
@else
    @if ( isset($attribute->options[$entity->{$attribute->slug}]) )
        {!! textUrls2Links($attribute->options[$entity->{$attribute->slug}]) !!}
    @endif
@endif