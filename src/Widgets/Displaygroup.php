<?php namespace Sanatorium\Inputs\Widgets;

class Displaygroup {

    public function __construct()
    {
        $this->groups = app('sanatorium.inputs.group');

        $this->attributes = app('platform.attributes');
    }

    public function show($entity, $group_name, $style = 'table', $show_empty = null, $hide_slugs = [], $hide_types = [])
    {
        if ( is_numeric($group_name) )
            $group = $this->groups->find($group_name);
        else
            $group = $this->groups->where('name', $group_name)->first();

        // Input group of given name not found
        if ( !$group )
            return null;

        // If show_empty is null decide
        if ( is_null($show_empty) )
            $show_empty = ($style == 'table' ? false : true);

        return view('sanatorium/inputs::widgets/displaygroup', compact('entity', 'group', 'style', 'show_empty', 'hide_slugs', 'hide_types'));
    }

}