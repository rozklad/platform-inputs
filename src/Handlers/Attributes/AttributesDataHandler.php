<?php

namespace Sanatorium\Inputs\Handlers\Attributes;

use Platform\Attributes\Handlers\DataHandler;
use Platform\Attributes\Handlers\DataHandlerInterface;

class AttributesDataHandler extends DataHandler implements DataHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function prepare(array $data)
    {
        dd($data);
        $options = [];

        foreach (array_get($data, 'options', []) as $option) {
            if (!  $option['value'] && ! $option['label']) {
                continue;
            }

            $options[trim($option['value'])] = trim($option['label']);
        }

        return array_merge(array_except($data, 'options'), compact('options'));
    }
}
