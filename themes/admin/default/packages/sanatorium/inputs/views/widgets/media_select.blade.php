{{ Asset::queue('selectize', 'selectize/js/selectize.js', 'jquery') }}
{{ Asset::queue('selectize', 'selectize/css/selectize.bootstrap3.css') }}

@section('styles')
@parent
<style type="text/css">
.media-select-upload-label {
	height: 60px;
	display: block;
	cursor: pointer;
	position: relative;
}
.media-select-dropdown-img {
	width: 60px;
	height: 60px;
	line-height: 60px;
	margin-right: 20px;
	display: inline-block;
}
.media-select-dropdown-img img {
	max-width: 60px;
	max-height: 60px
}
.media-select-upload-label .media-select-upload-progress.progress {
	margin-bottom: 0;
	position: absolute;
	bottom: -7px;
	left: 0;
	right: 0;
	width: 100%;
}
.media-select-upload-label .icon {
	color: #CCC;
	position: absolute;
	right: 20px;
	top: 20px;
}
.media-select-upload-label .help {
	pointer-events: none;
	position: absolute;
	font-size: 14px;
	line-height: 14px;
	text-transform: uppercase;
	width: 100%;
	left: 0;
	right: 0;
	top: 50%;
	margin-top: -7px;
}
</style>
@stop

@section('scripts')
@parent
<script type="text/javascript">
$(function(){

	var $statusText = $('.media-select-uploaded-file');

	$('.media-select').selectize({
	    valueField: 'id',
	    labelField: 'name',
	    searchField: 'name',
	    create: false,
	    options: [
	    	@foreach($media as $medium)
	    		{
	    			'name': "{{$medium->name}}",
	    			'id': {{$medium->id}},
	    			'image': "{{route('media.view', $medium->path)}}"
	    		},
	    	@endforeach
	    ],
	    render: {
	        item: function(item, escape) {
	            return '<div>' +
	            	'<div class="media-select-dropdown-img">' +
            			'<img src="' + item.image + '" alt="' + item.name + '">' +
            		'</div>' +
	                '<span class="title bold">' +
	                    item.name +
	                '</span>' +
	            '</div>';
	        },
	        option: function(item, escape) {
	            return '<div>' +
	            	'<div class="media-select-dropdown-img">' +
            			'<img src="' + item.image + '" alt="' + item.name + '">' +
            		'</div>' +
	                '<span class="title bold">' +
	                    item.name +
	                '</span>' +
	            '</div>';
	        }
	    }
	});

	$('#media-select-upload').change(function(event){

		var formdata = new FormData(),
			file = document.getElementById("media-select-upload").files[0];
		formdata.append("file", file);

		$.ajax({
			url 				: "{{ route('sanatorium.inputs.dropzone.upload.single') }}",
			type 				: "POST",
			cache 				: false,
			processData 		: false, 
            contentType 		: false,
			data 				: formdata,
			enctype 			: 'multipart/form-data'
        }).done(function( data ) {

        	var item = {
          		'id': data.id,
          		'name': data.name,
          		'image': data.image
          	};
          	var selectize = $('.media-select')[0].selectize;
          	selectize.addOption(item);
          	selectize.addItem(data.id);

          	// Indicate loading finished
          	$statusText.html('<span class="text-success">{{ trans('common.success') }}</span>');
          	$('.media-select-upload-progress').addClass('invisible');
          	
        });

        // Indicate loading
        $('.media-select-upload-progress').removeClass('invisible');
        $statusText.html(file.name);

        return false;

	});
});
</script>
@stop

<div class="row">

	<div class="col-xs-6">
		
		<select name="media_id" id="media_id" class="media-select">

			{{-- Current value --}}
			@if ( $single )
				
				@if ( $object->media_id )
				
					<option value="{{ $object->media_id }}"></option>
				
				@endif

			@endif

		</select>

	</div>

	<div class="col-xs-6">
		
		<input type="file" id="media-select-upload" class="hidden">

		<label for="media-select-upload" class="selectize-input">

			<div class="media-select-upload-label">

				<span class="text-muted media-select-uploaded-file">

				</span>

				<div class="media-select-upload-progress progress invisible">
	        
			        <div class="progress-bar-indeterminate"></div>

			    </div>

			    <i class="fa fa-upload fa-3x icon"></i>

			    <span class="text-center help">{{ trans('sanatorium/inputs::types.file.click_upload') }}</span>

			</div>

		</label>

	</div>

</div>

