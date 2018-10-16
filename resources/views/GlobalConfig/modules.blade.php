@extends ('Layout.split84-nopanel')

@section('content_top')


@stop


@section('content_left')

<h1>Modules</h1>
<h2>
{{ Form::open(array('route' => array('Modules.install_or_remove', 0), 'method' => 'post', 'id' => 'IndexForm')) }}
<table>
	@foreach ($modules as $name => $module)
		<tr>
			<td><i class="fa fa-fw {{$module['icon']}}"></i></td>
			<td>{{$name}}</td>
			@if (isset($module['installed']))
				<td>{!! Form::checkbox($module['package'], 'remove', null, null, ['style' => 'simple']) !!}</td>
				<td>{{ HTML::linkRoute('Modules.remove', 'remove', $module['package']) }}</td>
			@else
				<td>{!! Form::checkbox($module['package'], 'install', null, null, ['style' => 'simple']) !!}</td>
				<td>{{ HTML::linkRoute('Modules.install', 'install', $module['package']) }}</td>
			@endif
		</tr>
	@endforeach
</table>
{!! Form::submit('Install / Remove') !!}
{{ Form::close() }}
</h2>
@stop
