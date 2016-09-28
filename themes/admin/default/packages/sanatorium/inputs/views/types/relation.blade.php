<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

    <label for="{{ $attribute->slug }}" class="control-label">
        {{{ transattr($attribute->slug, $attribute->name) }}}
    </label>

    <?php

    $relation = \Sanatorium\Inputs\Models\Relation::where('attribute_id', $attribute->id)->first();

    $relatable_objects = [];

    // If no relation found, do not return anything
    // @todo: solve more elegant
    if ( !is_object($relation) ) {
        echo 'Relation is not set up correctly (missing Relation object)';
        return null;
    }

    $relatable_class = app('sanatorium.inputs.relations')->getRelation($relation->relation);

    $relatable_objects = $relatable_class::all()->toArray();

    ?>

    <select class="form-control" name="{{ $attribute->slug }}{{ $relation->multiple ? '[]' : '' }}" id="{{ $attribute->slug }}" {{ $relation->multiple ? 'multiple' : '' }}>
        @foreach ( $relatable_objects as $relatable_object )
            <option value="{{ $relatable_object['id'] }}"
                <?php
                        if ( is_array($entity->{$attribute->slug}) ) {
                            foreach( $entity->{$attribute->slug} as $option ) {
                                if ( $relatable_object['id'] == $option ) {
                                    echo 'selected';
                                }
                            }
                        } elseif ( is_string($entity->{$attribute->slug}) ) {
                            if ( $entity->{$attribute->slug} == $relatable_object['id'] ) {
                                echo 'selected';
                            }
                        }
                ?>
            >
                {{ $relatable_object['name'] }}
            </option>
        @endforeach
    </select>

    <?php unset($selected_values); ?>

    <span class="help-block">{{{ Alert::onForm($attribute->slug) }}}</span>

</div>