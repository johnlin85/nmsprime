@extends ('Layout.split84-nopanel')

@section('content_top')


@stop


@section('content_left')

<h1>Modules</h1>
<h2>
<table>
	@foreach ($modules as $name => $module)
		<tr>
			<td><i class="fa fa-fw {{$module['icon']}}"></i></td>
			<td>{{$name}}</td>
			@if (isset($module['installed']))
				<td>{{ HTML::linkRoute('Modules.uninstall', 'uninstall', $module['package']) }}</td>
			@else
				<td>{{ HTML::linkRoute('Modules.install', 'install', $module['package']) }}</td>
			@endif
		</tr>
	@endforeach
</table>
</h2>
@stop
