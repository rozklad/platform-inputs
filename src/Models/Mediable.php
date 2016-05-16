<?php namespace Sanatorium\Inputs\Models;

use Illuminate\Database\Eloquent\Model;

class Mediable extends Model {

    public $morphClass = '{insertmorphclass}';

    public function media()
    {
        return $this->morphToMany('Sanatorium\Inputs\Models\Media', 'entity', 'media_assign');
    }

}