<?php

/**
 * Class CategoryType
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

use Platform\Attributes\Types\TypeInterface;

class CategoryType extends BaseType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'category';

}
