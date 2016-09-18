{{-- Tags --}}
@if ( is_array($entity->{$attribute->slug}) )
    @if ( !empty($entity->{$attribute->slug}) )
        @foreach( $entity->{$attribute->slug} as $tag )
            <span class="label label-primary value-tag">{!! $tag !!}</span>
        @endforeach
    @endif
@endif