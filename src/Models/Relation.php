<?php namespace Sanatorium\Inputs\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model {


    /**
     * {@inheritDoc}
     */
    protected $table = 'attribute_relations';

    public function attribute()
    {
        return $this->belongsTo('Platform\Attributes\Models\Attribute');
    }

}
