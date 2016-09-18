{{-- Date --}}
@if ( $entity->{$attribute->slug} )
    {!! date('j.n.Y', strtotime($entity->{$attribute->slug})) !!}
@endif