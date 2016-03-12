{{ Asset::queue('dropzone', 'sanatorium/inputs::dropzone/dropzone.js') }}
{{ Asset::queue('dropzone', 'sanatorium/inputs::dropzone/dropzone.css') }}

@section('scripts')
<script type="text/javascript">
Dropzone.options.attributeDropzone{{{ ucfirst(camel_case($attribute->slug)) }}} = {
    url: '{{ route('sanatorium.inputs.dropzone.upload') }}',
    previewTemplate: document.querySelector('#template-container').innerHTML,
    previewsContainer: ".dropzone-previews-{{{ $attribute->slug }}}",
    uploadMultiple: true,
    parallelUploads: 100,
    maxFiles: 100,
    sending: function(file, xhr, formData) {
    	formData.append("_token", '{{ csrf_token() }}');
    	formData.append("entity_type", '{{ str_replace('\\', '(BACKSLASH)', get_class($entity)) }}');
    	formData.append("entity_id", '{{ $entity->id }}');
    	formData.append("attribute_slug", '{{ $attribute->slug }}');
    },
    success: function(file, response) {

    }
  };

function activateFiles() {
    $('#attribute-dropzone-{{{ $attribute->slug }}}')
        .css({
            'cursor': 'pointer'
        })
        .on('click', function(e)
        {
            e.preventDefault();
        });
}


$(function(){
	

    $('#attribute-dropzone-{{{ $attribute->slug }}} button.btn-delete')
    .on('click', function(e)
    {
        e.preventDefault();
        var that = $(this).parents('.dz-preview');
        $.ajax({
            url: '{{ route('sanatorium.inputs.dropzone.delete') }}',
            type: 'delete',
            data: {
                media_id : $(this).data('medium-id')
            },
            dataType: 'json',
            success : function(data)
            {
                if(data.status)
                {
                    that.remove();
                }
            },
            error : function(a,b,c)
            {
                console.log(a,b,c);
            }

        });
    });
});
</script>
@stop

<div class="form-group{{ Alert::onForm($attribute->slug, ' has-error') }}">

	<label for="{{ $attribute->slug }}">

		@if ($attribute->description)
		<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ $attribute->description }}"></i>
		@endif

		{{{ $attribute->name }}} ({{{ $attribute->slug }}}) 

	</label>
    <div class="dropzone dropzone-previews-{{{ $attribute->slug }}}" id="attribute-dropzone-{{{ $attribute->slug }}}" style="padding-bottom:300px;">
        {{-- Dropzone preview item --}}
        <div id="template-container" class="hidden">
            <div class="dz-preview dz-file-preview">
                  <div class="dz-details">
                    <div class="dz-filename"><span data-dz-name></span></div>
                    <div class="dz-size" data-dz-size></div>
                    <img data-dz-thumbnail />
                </div>
                <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                <div class="dz-success-mark"><span>✔</span></div>
                <div class="dz-error-mark"><span>✘</span></div>
                <div class="dz-error-message"><span data-dz-errormessage></span></div>
            </div>
        </div>

        {{-- Default Dropzone message --}}
		<div class="dz-message dz-default" data-dz-message>
            <div class="upload__instructions">
                <div class="dnd"></div>

                <i class="fa fa-upload fa-5x"></i>
                <h4>{{ trans('sanatorium/inputs::types.file.select') }}</h4>
                <p class="lead">{{ trans('sanatorium/inputs::types.file.allowed') }}</p>
                <p class="small">
                    <i>
                        {{ implode(', ', config('cartalyst.filesystem.allowed_mimes')) }}
                    </i>
                </p>

            </div>
        </div>

        {{-- Already uploaded file --}}
		@foreach( $entity->mediaByTag($attribute->slug) as $medium )
            <div class="dz-preview dz-file-preview">
                  <div class="dz-details">
                    <div class="dz-filename">{{ $medium->name }}</div>
                    <div class="dz-size">{{ formatBytes($medium->size) }}</div>
                    @if ( $medium->is_image )
                        <img src="{{ route('thumb.view', $medium->path) . '?w=250&h=250' }}" class="img-preview" alt="~" title="{{{ $attribute->name }}}" style="max-width:100%;height:auto;">
                        @else
                        @if ( ($medium->mime == 'audio/ogg') || ($medium->mime == 'video/mp4') || ($medium->mime == 'video/ogg') )

                        <i class="fa fa-file-movie-o fa-5x"></i>

                        @elseif ( $medium->mime == 'application/zip')

                        <i class="fa fa-file-zip-o fa-5x"></i>

                        @elseif ( $medium->mime == 'application/pdf')

                        <i class="fa fa-file-pdf-o fa-5x"></i>

                        @else

                        <i class="fa fa-file-o fa-5x"></i>

                        @endif
                    @endif
                </div>
                <div class="dz-success-mark"><span>✔</span></div>
                <div class="dz-error-mark"><span>✘</span></div>
                <div class="dz-error-message"><span data-dz-errormessage></span></div>
                <button class="btn btn-link btn-delete pull-right" data-medium-id="{{{ $medium->id }}}">
                    <i class="fa fa-times"></i>
                </button>
            </div>
		@endforeach
	</div>

	<span class="help-block"></span>

</div>