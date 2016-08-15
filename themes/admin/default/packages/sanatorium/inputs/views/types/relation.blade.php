<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

    <label for="{{ $attribute->slug }}" class="control-label">
        {{{ $attribute->name }}}
    </label>

    <?php

    $relation = \Sanatorium\Inputs\Models\Relation::where('attribute_id', $attribute->id)->first();

    $relatable_objects = [];

    if ($relation) {

        $relatable_class = app('sanatorium.inputs.relations')->getRelation($relation->relation);

        $relatable_objects = $relatable_class::all()->toArray();

    }

    if ( !is_array($entity->{$attribute->slug}) ) {
        $selected = [ $entity->{$attribute->slug} ];
    } else {
        $selected = $entity->{$attribute->slug};
    }

    ?>

    <select class="form-control" name="{{ $attribute->slug }}{{ $relation->multiple ? '[]' : '' }}" id="{{ $attribute->slug }}" {{ $relation->multiple ? 'multiple' : '' }}>
    @foreach ( $relatable_objects as $relatable_object )
        <option value="{{ $relatable_object['id'] }}" {{ in_array($relatable_object['id'], $selected) ? 'selected' : '' }}>{{ $relatable_object['name'] }}</option>
    @endforeach
    </select>

    <span class="help-block">{{{ Alert::onForm($attribute->slug) }}}</span>

</div>