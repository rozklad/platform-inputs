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
.media-single-preview {
	text-align: center;
	height: 300px;
	line-height: 300px;
}
.media-single-preview img {
	display: inline-block;
}
</style>
@stop

@section('scripts')
@parent
<script type="text/javascript">
$(function(){

	var $statusText = $('.media-select-uploaded-file-{{ $attribute->slug }}');

	$('#media-select-upload-{{ $attribute->slug }}').change(function(event){

		var formdata = new FormData(),
			file = document.getElementById("media-select-upload-{{ $attribute->slug }}").files[0];
		formdata.append("file", file);
		formdata.append("entity_type", "{{ str_replace('\\', '(BACKSLASH)', get_class($entity)) }}");
		formdata.append("entity_id", {{ $entity->id }});
		formdata.append("attribute_slug", "{{ $attribute->slug }}");

		$.ajax({
			url 				: "{{ route('sanatorium.inputs.dropzone.upload.avatar') }}",
			type 				: "POST",
			cache 				: false,
			processData 		: false, 
            contentType 		: false,
			data 				: formdata,
			enctype 			: 'multipart/form-data'
        }).done(function( data ) {
        	
          	// Indicate loading finished
          	$statusText.html('<span class="text-success">{{ trans('common.success') }}</span>');
          	$('.media-select-upload-progress-{{ $attribute->slug }}').addClass('invisible');
          	
          	$('#preview-{{ $attribute->slug }}').attr('src', data.thumbnail);
        });

        // Indicate loading
        $('.media-select-upload-progress-{{ $attribute->slug }}').removeClass('invisible');
        $statusText.html(file.name);

        return false;

	});
});
</script>
@stop


<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}">

		@if ($attribute->description)
		<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
		@endif

		{{{ $attribute->name }}}

	</label>

	<div class="media-preview media-single-preview">

		@foreach( $entity->mediaByTag($attribute->slug) as $medium)

			<img src="{{ $medium->thumbnail }}" alt="{{ $medium->name }}" id="preview-{{ $attribute->slug }}">

		@endforeach

	</div>

	<input type="file" id="media-select-upload-{{ $attribute->slug }}" class="hidden">

	<label for="media-select-upload-{{ $attribute->slug }}" class="selectize-input">

	<div class="media-select-upload-label">

		<span class="text-muted media-select-uploaded-file-{{ $attribute->slug }}">

		</span>

		<div class="media-select-upload-progress-{{ $attribute->slug }} progress invisible">

			<div class="progress-bar-indeterminate"></div>

		</div>

		<i class="fa fa-upload fa-3x icon"></i>

		<span class="text-center help">{{ trans('sanatorium/inputs::types.file.click_upload') }}</span>

	</div>

	<span class="help-block"></span>

</div>
