@foreach( $settings as $key => $setting )

    <div class="form-group">

        <label for="{{ $key }}" class="control-label">
            {{ trans($setting['name']) }}
        </label>

        <input type="{{ isset($setting['type']) ? $setting['type'] : 'text' }}"
               class="form-control"
               name="settings[{{ $key }}]"
               id="{{ $key }}"
               value="{{ isset($values[$key]) ? $values[$key] : $setting['default'] }}"
               placeholder="{{ trans($setting['name']) }}"
               data-parsley-trigger="change"
               data-parsley-name="{{ $setting['name'] }}"
               <?php

               if ( isset($setting['validation']) ) {

                   $rules = explode('|', $setting['validation']);
                   $parsley_rules = [];

                   foreach ( $rules as $rule ) {

                       switch(true) {

                           case $rule == 'numeric':

                               $parsley_rules[] = 'data-parsley-type="integer"';

                               break;

                           case strpos($rule, 'min:') === 0:

                               $parts = explode(':', $rule);
                               $parsley_rules[] = 'data-parsley-gte="#'.$parts[1].'" data-parsley-msg-name="gte"';

                               break;

                           case strpos($rule, 'max:') === 0:

                               $parts = explode(':', $rule);
                               $parsley_rules[] = 'data-parsley-lte="#'.$parts[1].'" data-parsley-msg-name="lte"';

                               break;

                       }

                   }

               }

               ?>
                {!! implode(' ', $parsley_rules) !!}
        >

        <span class="help-block">{{{ Alert::onForm($key) }}}</span>

    </div>

@endforeach