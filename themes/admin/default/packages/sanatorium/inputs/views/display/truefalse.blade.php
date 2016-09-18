@if ( $entity->{$attribute->slug} )
    {{ trans('common.yes') }}
@elseif ( $entity->{$attribute->slug} === 0 || $entity->{$attribute->slug} === false )
    {{ trans('common.no') }}
@endif