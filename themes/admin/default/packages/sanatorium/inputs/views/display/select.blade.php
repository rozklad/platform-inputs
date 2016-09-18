{{-- Select --}}
@if ( is_array($entity->{$attribute->slug}) )
    @if ( !empty($entity->{$attribute->slug}) )
        @foreach( $entity->{$attribute->slug} as $option_key )
            {!! textUrls2Links($attribute->options[$option_key]) !!}
        @endforeach
    @endif
@else
    {!! textUrls2Links($attribute->options[$entity->{$attribute->slug}]) !!}
@endif