<?php

/**
 * Class CountryType
 *
 * Part of the Sanatorium\Inputs\Types extension.
 *
 * NOTICE OF LICENSE
 *
 * @package    Sanatorium\Inputs\Types
 * @version    1.0.0
 * @author     Sanatorium
 * @license    WTFPL
 * @copyright  (c) 2015-2016, Sanatorium
 * @link       http://sanatorium.ninja
 */

namespace Sanatorium\Inputs\Types;

use Cartalyst\Attributes\EntityInterface;
use Platform\Attributes\Models\Attribute;

class CountryType extends BaseType
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'country';

    /**
     * {@inheritDoc}
     */
    public function getEntityFormHtml(Attribute $attribute, EntityInterface $entity)
    {
        $countries_file_path = __DIR__ . '/../../storage/countries.json';

        if ( !file_exists($countries_file_path) )
            return null;

        $countries = json_decode( file_get_contents($countries_file_path), true);
        return view("sanatorium/inputs::types/country", compact('attribute', 'entity', 'countries'));
    }

}
