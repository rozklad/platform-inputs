<?php namespace Sanatorium\Inputs\Controllers\Admin;
/**
 * Extended support for attribute settings etc.
 *
 * @author  Sanatorium <sanatorium.ninja>
 * @package inputs
 */


use Platform\Access\Controllers\AdminController;

class AttributesController extends AdminController {

    public function settings($type = null)
    {
        $types = app('platform.attributes.manager')->getTypes();

        // Given type does not exist
        if ( !isset($types[$type]) )
            return null;

        if ( !isset($types[$type]->settings) )
            return null;

        $settings = $types[$type]->settings ?: [];

        if ( request()->get('html') )
            return $this->generateHtml($settings);

        return $settings;

    }

    public function generateHtml($settings = [], $values = [])
    {
        $id = request()->get('id');

        $setting_object = \Sanatorium\Inputs\Models\AttributeSettings::where( 'attribute_id', $id )->first();

        if ( $setting_object )
        {
            $values = json_decode($setting_object->settings, true);
        }

        return view('sanatorium/inputs::settings', compact('settings', 'values'));
    }

}
