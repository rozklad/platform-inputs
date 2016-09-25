<?php /*
{{-- Queue assets --}}
{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
<script type="text/javascript">

	var Repeater = {

		init: function() {

			this.addEventListeners();

		},

		addEventListeners: function() {

			var self = this;

			$('[data-option-add]').unbind('click').click(function(event){
			
				event.preventDefault();
			
				self.addOption();
			
				self.addEventListeners();
			});

			$('[data-option-remove]').unbind('click').click(function(event){
				
				event.preventDefault();
				
				self.removeOption( $(this) );
			
				self.addEventListeners();
			});

		},

		addOption: function() {

			var template = _.template($('[data-option-template]').html());

			$('.options').append(
				template()
			);
		
		},

		removeOption: function($this) {

			$this.parents('.form-repeater:first').remove();
		}

	};

	$(function(){
		
		Repeater.init();

	});
</script>
@stop

<div class="{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}" class="control-label">
		{{{ transattr($attribute->slug, $attribute->name) }}}
	</label>

	<div class="options">

		{{-- Already has values, show forms --}}
		@if ( isset( $entity->{$attribute->slug} ) )

			{{-- Support legacy --}}
			@if ( is_string( $entity->{$attribute->slug} ) )
				
				<?php $entity->{$attribute->slug} = explode("\n", $entity->{$attribute->slug}); ?>

			@endif

			@if ( is_array( $entity->{$attribute->slug} ) )

				@foreach( $entity->{$attribute->slug} as $value )

				<div class="form-group form-repeater">
					
					<div class="input-group">

						<input type="text" name="{{ $attribute->slug }}[]" value="{{ $value }}" class="form-control"  data-parsley-ui-enabled="false">

						<span class="input-group-btn">
							<button class="btn btn-md btn-default" data-option-add><i class="fa fa-plus"></i></button>
						</span>

						<span class="input-group-btn">
							<button class="btn btn-md btn-default" data-option-remove><i class="fa fa-trash-o"></i></button>
						</span>

					</div>

				</div>

				<p class="hidden">
					<a href="{{ $value }}" class="btn btn-link btn-md" target="_blank" style="max-width:150px;">
						{{ $value }}
					</a>
				</p>

				@endforeach

			@endif

		@endif

		{{-- Content is empty, show empty form --}}
		@if ( ! $entity->{$attribute->slug} ) 

		<div class="form-group form-repeater">
			
			<div class="input-group">

				<input type="text" name="{{ $attribute->slug }}[]" class="form-control"  data-parsley-ui-enabled="false">

				<span class="input-group-btn">
					<button class="btn btn-md btn-default" data-option-add><i class="fa fa-plus"></i></button>
				</span>

				<span class="input-group-btn">
					<button class="btn btn-md btn-default" data-option-remove><i class="fa fa-trash-o"></i></button>
				</span>
			
			</div>

		</div>

		@endif

	</div>

</div>

<script type="text/template" data-option-template>

	<div class="form-group form-repeater">

		<div class="input-group">

			<input class="form-control" name="{{ $attribute->slug }}[]" type="text" data-parsley-ui-enabled="false">

			<span class="input-group-btn">
				<button class="btn btn-md btn-default" data-option-add><i class="fa fa-plus"></i></button>
			</span>

			<span class="input-group-btn">
				<button class="btn btn-md btn-default" data-option-remove><i class="fa fa-trash-o"></i></button>
			</span>

		</div>

	</div>

</script>
*/ ?>

{{-- Queue assets --}}
{{ Asset::queue('vue', 'sanatorium/inputs::vue/vue.min.js') }}

{{-- Inline styles --}}
@section('styles')
    @parent
    <style type="text/css">
        .repeater--row {
            padding-bottom: 10px;
        }
        .repeater--field--row {
            padding-bottom: 10px;
        }
    </style>
@stop

{{-- Inline scripts --}}
@section('scripts')
    @parent
    <script type="text/javascript">

        // Repeater field
        var Repeater{{ str_replace('-', '_', str_slug($attribute->slug)) }} = new Vue({
            el: '#repeater-{{ str_replace('-', '_', str_slug($attribute->slug)) }}',
            data: {
                {{-- Values --}}
                @if ( is_array( $entity->{$attribute->slug} ) )
                values   : {!! json_encode($entity->{$attribute->slug}) !!},
                @else
                values   : [],
                @endif

                {{-- Repeaters --}}
                @if ( !empty($attribute->options) )
                repeaters: [
                    @if ( is_array( $entity->{$attribute->slug} ) )
                        @foreach( $entity->{$attribute->slug} as $value )
                            {!! json_encode($attribute->options) !!},
                        @endforeach
                    @else
                        {!! json_encode($attribute->options) !!}
                    @endif
                ],
                @else
                repeaters: [
                    @if ( is_array( $entity->{$attribute->slug} ) )
                        @foreach( $entity->{$attribute->slug} as $value )
                    {
                        fields: [
                            {
                                label      : '{{ transattr($attribute->slug, $attribute->name, null, 'name')}}',
                                placeholder: '{{ transattr($attribute->slug, $attribute->description, null, 'description') }}',
                                value      : '{{ $value }}'
                            }
                        ]
                    },
                        @endforeach
                        @else
                    {
                        fields: [
                            {
                                label      : '{{ transattr($attribute->slug, $attribute->name, null, 'name') }}',
                                placeholder: '{{ transattr($attribute->slug, $attribute->description, null, 'description') }}',
                                value      : ''
                            }
                        ]
                    }
                    @endif
                ],
                @endif
            }
            ,
            methods: {
                add: function(index, repeater) {
                    @if ( !empty($attribute->options) )
                        this.repeaters.splice(index + 1, 0, {!! json_encode($attribute->options) !!});
                        this.values.splice(index + 1, 0, {});
                    @else
                        this.repeaters.splice(index + 1, 0, {
                            fields:
                                    [
                                        {
                                            label      : '{{ transattr($attribute->slug, $attribute->name, null, 'name') }}',
                                            placeholder: '{{ transattr($attribute->slug, $attribute->description, null, 'description') }}',
                                            value      : ''
                                        }
                                    ]
                        });
                        this.values.splice(index + 1, 0, '');
                    @endif
                },
                remove: function(repeater, repeaterindex) {
                    this.repeaters.$remove(repeater);
                    this.values.splice(repeaterindex, 1);
                }
            }
        });
    </script>
@stop

<div class="form-group">

    <label class="control-label">{{ transattr($attribute->slug, $attribute->name, null, 'name')}}</label>

    <div id="repeater-{{ str_replace('-', '_', str_slug($attribute->slug)) }}">

        <div v-for="(repeaterindex, repeater) in repeaters">

            <div class="row repeater--row">
                <div class="col-sm-10">
                    <div v-for="(fieldindex, field) in repeater.fields" class="repeater--field--row">

                        @if ( !empty($attribute->options) )

                            @if ( !isset($countries) )

                                <?php
                                // @todo: dynamically resolve extensions folder
                                $countries_file_path = base_path('extensions/sanatorium/inputs/storage/countries.json');

                                $countries = json_decode( file_get_contents($countries_file_path), true);
                                ?>

                            @endif

                            {{-- Field types --}}
                            <div v-if="field.type == 'country'"> {{-- Country --}}
                                <select class="form-control no-selectize" name="{{ $attribute->slug }}[@{{ repeaterindex }}][@{{ fieldindex }}]" placeholder="@{{ field.label }}" v-model="values[repeaterindex][fieldindex]" data-selectize-disabled>
                                    @foreach( $countries as $country )
                                        <option value="{{ $country['code'] }}">{{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div v-else> {{-- Default --}}
                                <input type="text" class="form-control" name="{{ $attribute->slug }}[@{{ repeaterindex }}][@{{ fieldindex }}]" placeholder="@{{ field.label }}" value="@{{ values[repeaterindex][fieldindex] }}" v-model="values[repeaterindex][fieldindex]">
                            </div>

                        @else

                            <input type="text" class="form-control" name="{{ $attribute->slug }}[]" placeholder="@{{ field.description }}" value="@{{ field.value }}" v-model="values[repeaterindex]">

                        @endif
                    </div>
                </div>
                <div class="col-sm-2 text-right">
                    <button type="button" @click="add(repeaterindex, repeater)" class="btn btn-default">
                        <span class="sr-only">{{ trans('action.add') }}</span>
                        <i class="fa fa-plus"></i>
                    </button>
                    <button type="button" @click="remove(repeater, repeaterindex)" class="btn btn-default">
                        <span class="sr-only">{{ trans('action.delete') }}</span>
                        <i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>