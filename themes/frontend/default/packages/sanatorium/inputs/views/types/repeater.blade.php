<ul>
	@foreach( $entity->{$attribute->slug} as $value )
	<li>
		<a href="{{ $value }}" target="_blank">
			{{ $value }}
		</a>
	</li>
	@endforeach
</ul>