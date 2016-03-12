@section('styles')
@parent
<style type="text/css">
.table .middle {
    vertical-align: middle;
}
.table-downloads tbody tr:first-child td {
    border-top: 0;
}
</style>
@stop

<table class="table table-downloads" summary="{{ $attribute->name }}">
<tbody>
@foreach( $entity->mediaByTag($attribute->slug) as $medium )
    <tr class="row">
        <td class="col-xs-2 text-center middle">
            @if ( $medium->is_image )
                <img src="{{ route('thumb.view', $medium->path) . '?w=60&h=60' }}" class="img-preview" alt="~" title="{{{ $attribute->name }}}" style="max-width:100%;height:auto;">
            @else
                @if ( ($medium->mime == 'audio/ogg') || ($medium->mime == 'video/mp4') || ($medium->mime == 'video/ogg') )

                <i class="fa fa-file-movie-o fa-2x"></i>

                @elseif ( $medium->mime == 'application/zip')

                <i class="fa fa-file-zip-o fa-2x"></i>

                @elseif ( $medium->mime == 'application/pdf')

                <i class="fa fa-file-pdf-o fa-2x"></i>

                @else

                <i class="fa fa-file-o fa-2x"></i>

                @endif
            @endif
        </td>
        <td class="col-xs-8">
            <h5>
                <a href="{{ route('media.download', [$medium->path]) }}">
                    {{ $medium->name }}
                </a>
            </h5>
            <p>
                {{ $medium->mime }}
                <br>
                <small>{{ formatBytes($medium->size) }}</small>
            </p>
        </td>
        <td class="col-xs-2 middle">
            <a href="{{ route('media.download', [$medium->path]) }}" class="btn btn-success btn-block">
                {{ trans('action.download') }}
            </a>
        </td>
    </tr>
@endforeach
</tbody>
</table>
