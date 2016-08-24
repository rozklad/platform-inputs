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
		{{{ $attribute->name }}}
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

				<div class="form-inline form-repeater">
					
					<div class="form-group">

						<input type="text" name="{{ $attribute->slug }}[]" value="{{ $value }}" class="form-control"  data-parsley-ui-enabled="false">
						
						<button class="btn btn-md btn-default" data-option-add><i class="fa fa-plus"></i></button>

						<button class="btn btn-md btn-default" data-option-remove><i class="fa fa-trash-o"></i></button>
					
						<a href="{{ $value }}" class="btn btn-link btn-md" target="_blank" style="max-width:150px;">
							{{ $value }}
						</a>

					</div>

				</div>

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
