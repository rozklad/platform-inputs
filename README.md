# sanatorium/inputs

Extended input types

## Documentation

### Available input types

#### File

Pick single file from media library.
*child type to Media*

#### Gallery

Pick multiple images from media library.
*child type to Media*

#### Image

Pick single image from media library.
*child type to Media*

#### Media

Pick multiple files from media library, also a parent for File, Gallery, Image, Video.

#### Repeater

TBD

#### Switchery

One or multiple (allowOptions) checkboxes with "switchery look".

#### Truefalse

Single true/false value.

#### Video

Pick single video from media library.
*child type to Media*

#### WYSIWYG

WYSIWYG editor input field.

### Create custom type

1. Create new class in Sanatorium\Inputs\Types namespace, that might look like this

        <?php

        namespace Sanatorium\Inputs\Types;

        class DateType extends BaseType
        {
            /**
             * {@inheritDoc}
             */
            protected $identifier = 'date';

        }

2. Register that class in Sanatorium\Inputs\Providers\InputServiceProvider::registerTypes() method

        $types = [
          ...
          'date'		 	=> new Types\DateType,
        ];

3. Create templates to display the custom type on frontend and backend in sanatorium/inputs::types/{typeidentifider} theme path. (For example: /themes/admin/default/packages/sanatorium/input/views/types/date.blade.php and /themes/frontend/default/packages/sanatorium/input/views/types/date.blade.php)

## Changelog

- 0.1.8 - 2016-16-05 - Basic readme file

## Support

Support not available.